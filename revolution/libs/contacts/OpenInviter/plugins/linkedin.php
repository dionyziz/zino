<?php
$_pluginInfo=array(
	'name'=>'LinkedIn',
	'version'=>'1.0.5',
	'description'=>"Get the contacts from a LinkedIn account",
	'base_version'=>'1.6.3',
	'type'=>'social',
	'check_url'=>'http://www.linkedin.com'
	);
/**
 * LinkedIn
 * 
 * Imports user's contacts from LinkedIn and send messages
 * using LinkedIn's internal messaging system
 * 
 * @author OpenInviter
 * @version 1.0.4
 */
class linkedin extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='email';
	public $internalError=false;
	public $allowed_domains=false;
	
	public $debug_array=array(
				'initial_get'=>'session_password',
				'login_post'=>'window.location.replace',
				'get_friends'=>'csrfToken',
				'profile_friend_url'=>'msgToConns?displayCreate',
				'message_url'=>'connectionNames',
				'send_message'=>'msgToConns?displayCreate',
				);
	
	/**
	 * Login function
	 * 
	 * Makes all the necessary requests to authenticate
	 * the current user to the server.
	 * 
	 * @param string $user The current user.
	 * @param string $pass The password for the current user.
	 * @return bool TRUE if the current user was authenticated successfully, FALSE otherwise.
	 */
	public function login($user,$pass)
		{
		$this->resetDebugger();
		$this->service='linkedin';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
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
		//$url_friends="http://www.linkedin.com/connectionsnojs?trk=cnx_nojslink";
                $url_friends="http://www.linkedin.com/connectionsnojs?split_page=1";
		$this->login_ok=$url_friends;
		return true;
		}

	/**
	 * Get the current user's contacts
	 * 
	 * Makes all the necesarry requests to import
	 * the current user's contacts
	 * 
	 * @return mixed The array if contacts if importing was successful, FALSE otherwise.
	 */	
	public function getMyContacts()
		{
		if (!$this->login_ok)
			{
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		else $url=$this->login_ok;
                $inti = 1;
                $boolContinue = true;
                $test = array();
//Thanks to michbx for the pagination code
		while($boolContinue){
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
		$doc=new DOMDocument();
                libxml_use_internal_errors(true);
                if (!empty($res)) $doc->loadHTML($res);
                libxml_use_internal_errors(false);
		$xpath=new DOMXPath($doc);
                $query="//a[@class='fn']";
                $data=$xpath->query($query);
		foreach ($data as $node)
				{
				$href=(string)$node->getAttribute('href');
                                $contacts[html_entity_decode(str_replace("%2Ecnj_1",".cnj_1",$href))]=$node->nodeValue;
                                if(in_array($node->nodeValue, $test)){$boolContinue = false; }
                                $test[] = $node->nodeValue;                                
				}
                $strBegin = "http://www.linkedin.com/connectionsnojs?split_page=";
                $inti = $inti + 1;
                $url = $strBegin.$inti;
                }                
		return $contacts;
		}

	/**
	 * Send message to contacts
	 * 
	 * Sends a message to the contacts using
	 * the service's inernal messaging system
	 * 
	 * @param string $cookie_file The location of the cookies file for the current session
	 * @param string $message The message being sent to your contacts
	 * @param array $contacts An array of the contacts that will receive the message
	 * @return mixed FALSE on failure.
	 */
	public function sendMessage($session_id,$message,$contacts)
		{
		foreach($contacts as $href=>$name)
			{ 
			$friend_url="http://www.linkedin.com".html_entity_decode($href);
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
			$message_url=$this->getElementString($res,'connId=','"');
			$message_link="http://www.linkedin.com/msgToConns?displayCreate=&connId=".$message_url;
			$res=$this->get($message_link);
			
			if ($this->checkResponse('message_url',$res))
				$this->updateDebugBuffer('message_url',$message_link,'GET');
			else
				{
				$this->updateDebugBuffer('message_url',$message_link,'GET',false);
				$this->debugRequest();
				$this->stopPlugin();
				return false;
				}
		
			$form_action="http://www.linkedin.com/msgToConns";
			$post_elements=array('csrfToken'=>$this->getElementString($res,'name="csrfToken" value="','"'),
								'subject'=>$message['subject'],
								'body'=>$message['body'],
								'showRecipeints'=>'showRecipeints',
								'submit'=>'Send',
								'openSocialAppBodySuffix'=>'',
								'addMoreRcpts'=>'false',
								'fromEmail'=>$this->getElementString($res,'name="fromEmail" value="','"'),
								'connectionNames'=>$this->getElementString($res,'name="connectionNames" value="','"'),
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

	/**
	 * Terminate session
	 * 
	 * Terminates the current user's session,
	 * debugs the request and reset's the internal 
	 * debudder.
	 * 
	 * @return bool TRUE if the session was terminated successfully, FALSE otherwise.
	 */	
	public function logout()
		{
		if (!$this->checkSession()) return false;
		$res=$this->get("https://www.linkedin.com/secure/login?session_full_logout=&trk=hb_signout",true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	}	

?>