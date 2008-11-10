<?php
/*Import Friends from Hi5
 * You can send private message using Hi5 system to your Friends
 */
$_pluginName='Hi5';
$_pluginVersion='1.0.2';
$_pluginDescription="Get the contacts from Hi5";
$_requiredBaseVersion="1.5.0";
$_pluginType='social';
class hi5 extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	
	public $debug_array=array(
				'initial_get'=>'var _hbEC',
				'login_post'=>'friends',
				'url_friends'=>'alreadyInTopFriends',
				'url_message'=>'toIds',
				'send_message'=>'reqs'
				);
	
	public function login($user,$pass)
		{
		$this->resetDebugger();
		$this->service='hi5';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->curl=$this->init();
		
		//go to hi5
		$res=$this->get("http://www.hi5.com/");
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"http://www.hi5.com/",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://www.hi5.com/",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$form_action="http://www.hi5.com/friend/login.do";
		$post_elements=array(
							'email'=>$user,
							'password'=>$pass,
							'remember'=>'on'
							);
		//get the post variables and send post to form action
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
		$url_friends="http://www.hi5.com/friend/viewFriends.do";
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
		$res=$this->get($url,true);$contacts=array();$mail_contacts=array();$url_next=false;
		if ($this->checkResponse("url_friends",$res))
			$this->updateDebugBuffer('url_friends',$url,'GET');
		else
			{
			$this->updateDebugBuffer('url_friends',$url,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		//get friends
		do
			{
			$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
			$xpath=new DOMXPath($doc);$query="//a[@name='&lid=FriendBrowser_NameLink']";$data=$xpath->query($query);
			foreach ($data as $node)
				$contacts[str_replace('/friend/profile/displayProfile.do?userid=','',$node->getAttribute('href'))]=(string)$node->getAttribute('title');
			$url_next=$this->getElementString($res,'text_pagination_previous"> <a href="','"');
			//go to next page of friends
			if ($url_next) $res=$this->get("http://hi5.com{$url_next}",true);
			}
		while($url_next);
		
		return $contacts;
		}

	public function sendMessage($cookie_file,$message,$contacts)
		{
		//get the cookie
		$this->curl=$this->init($cookie_file);
		foreach($contacts as $id=>$name)
			{
			$url_send_message="http://hi5.com/friend/mail/displayComposeMail.do?toIds={$id}";
			//go to the url friend
			$res=$this->get($url_send_message);
			if ($this->checkResponse("url_message",$res))
				$this->updateDebugBuffer('url_message',$url_send_message,'GET');
			else
				{
				$this->updateDebugBuffer('url_message',$url_send_message,'GET',false);
				$this->debugRequest();
				$this->stopPlugin();
				return false;
				}
			$form_action="http://hi5.com/friend/mail/sendMail.do";
			$post_elements=array('toIds'=>$this->getElementString($res,"idToName['","'"),
								 'subject'=>$message['subject'],
								 'method'=>'send',
								 'body'=>$message['body'],
								 'timestamp'=>$this->getElementString($res,'name="timestamp" value="','"'),
								 'mailOp'=>'',
								 'senderId'=>'',
								 'msgId'=>'',
								 'submitSend'=>'Send Message'
								);
			//get the post variables and send post to forn action
			$res=$this->post($form_action,$post_elements,true);
			if ($this->checkResponse("send_message",$res))
				$this->updateDebugBuffer('send_message',$url_send_message,'POST',true,$post_elements);
			else
				{
				$this->updateDebugBuffer('send_message',$url_send_message,'POST',false,$post_elements);
				$this->debugRequest();
				$this->stopPlugin();
				return false;
				}
			}
		}
		
	public function logout()
		{
		//go to logout url
		$res=$this->get("http://hi5.com/friend/logoff.do",true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	}	

?>