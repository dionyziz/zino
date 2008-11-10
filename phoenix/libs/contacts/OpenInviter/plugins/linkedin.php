<?php
/*Import Friends from Linkedin
 * You can send private message using Linkedin system to your Friends
 */
$_pluginName='LinkedIn';
$_pluginVersion='1.0.1';
$_pluginDescription="Get the contacts from LinkedIn";
$_requiredBaseVersion="1.5.0";
$_pluginType='social';
class linkedin extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	
	public $debug_array=array(
				'initial_get'=>'session_password',
				'login_post'=>'window.location.replace',
				'get_friends'=>'csrfToken',
				'profile_friend_url'=>'msgToConns?displayCreate',
				'message_url'=>'connectionNames',
				'send_message'=>'msgToConns?displayCreate',
				);
	
	public function login($user,$pass)
		{
		$this->resetDebugger();
		$this->service='linkedin';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->curl=$this->init();
		
		//go to linkedin
		$res=$this->get("https://www.linkedin.com/secure/login?trk=hb_signin");
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"https://www.linkedin.com/secure/login?trk=hb_signin",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"https://www.linkedin.com/secure/login?trk=hb_signin",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$form_action="https://www.linkedin.com/secure/login";
		$post_elements=array('csrfToken'=>'guest_token',
							 'session_key'=>$user,
							 'session_password'=>$pass,
							 'session_login'=>'Sign In',
							 'session_login'=>'',
							 'session_rikey'=>''
							);
		//get the post variables and send post to url login 
		$res=$this->post($form_action,$post_elements,true);
		if ($this->checkResponse("login_post",$res))
			$this->updateDebugBuffer('login_post',"{$form_action}",'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login_post',"{$form_action}",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$url_friends="http://www.linkedin.com/connectionsnojs?trk=cnx_nojslink";
		$this->login_ok=$url_friends;
		return true;
		}
		
	public function getMyContacts()
		{
		if (!$this->login_ok)
			{
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		else $url=$this->login_ok;
		//go to url friends
		$res=$this->get($url,true);
		if ($this->checkResponse('get_friends',$res))
			$this->updateDebugBuffer('get_friends',$url,'GET');
		else
			{
			$this->updateDebugBuffer('get_friends',$url,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		//get friends
		$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
		$xpath=new DOMXPath($doc);$query="//a[@class='fn']";$data=$xpath->query($query);
		foreach ($data as $node)
				$contacts[$node->getAttribute('href')]=$node->nodeValue;
		return $contacts;
		}

	public function sendMessage($cookie_file,$message,$contacts)
		{
		//get the cookie
		$this->curl=$this->init($cookie_file);
		foreach($contacts as $href=>$name)
			{
			//go to friend profile 
			$friend_url="http://www.linkedin.com".$href;
			$res=$this->get($friend_url,true);
			if ($this->checkResponse('profile_friend_url',$res))
				$this->updateDebugBuffer('profile_friend_url',$friend_url,'GET');
			else
				{
				$this->updateDebugBuffer('profile_friend_url',$friend_url,'GET',false);
				$this->debugRequest();
				$this->stopPlugin();
				return false;
				}
			//get the message page
			$message_url=$this->getElementString($res,'<li><a href="/msgToConns?displayCreate=&connId=','"');
			$message_link="http://www.linkedin.com/msgToConns?displayCreate=&connId=".$message_url;
			$res=$this->get($message_link,true);
			if ($this->checkResponse('message_url',$res))
				$this->updateDebugBuffer('message_url',$message_link,'GET');
			else
				{
				$this->updateDebugBuffer('message_url',$message_link,'GET',false);
				$this->debugRequest();
				$this->stopPlugin();
				return false;
				}
			
			//send the message
			$form_action="http://www.linkedin.com/msgToConns";
			$post_elements=array('csrfToken'=>$this->getElementString($res,'name="csrfToken" value="','"'),
								'subject'=>$message['subject'],
								'body'=>$message['body'],
								'showRecipeints'=>'showRecipeints',
								'submit'=>'Send',
								'openSocialAppBodySuffix'=>'',
								'addMoreRcpts'=>'false',
								'fromEmail'=>$this->getElementString($res,'name="fromEmail" value="','"'),
								'connectionNames'=>html_entity_decode($this->getElementString($res,'name="connectionNames" value="','"')),
								'allowEditRcpts'=>'true',
								'connectionIds'=>$this->getElementString($res,'name="connectionIds" value="','"'),
								'st'=>'',
								'fromName'=>$this->getElementString($res,'name="fromName" value="','"'),
								'goback'=>$this->getElementString($res,'name="goback" value="','"')
								);
			if (strpos($res,'fromEmail')!==false)
				{ 
				$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
				$xpath=new DOMXPath($doc);$query="//select[@name='fromEmail']";$data=$xpath->query($query);
				foreach ($data as $node)
					$post_elements['fromEmail']=$node->firstChild->getAttribute('value');
				} 
			$post_elements['subject']=$message['subject'];$post_elements['body']=$message['body'];
			$res=$this->post($form_action,$post_elements,true);
			if ($this->checkResponse('send_message',$res))
				$this->updateDebugBuffer('send_message',"{$form_action}",'POST',true,$post_elements);
			else
				{
				$this->updateDebugBuffer('send_message',"{$form_action}",'POST',false,$post_elements);
				$this->debugRequest();
				$this->stopPlugin();
				return false;
				}
			}
		}
		
	public function logout()
		{
		//go to logout url
		$res=$this->get("https://www.linkedin.com/secure/login?session_full_logout=&trk=hb_signout",true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	}	

?>