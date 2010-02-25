<?php
/*Import Friends from Meinvz
 * You can send message to your MeinVz Inbox
 */
$_pluginInfo=array(
	'name'=>'Meinvz',
	'version'=>'1.0.1',
	'description'=>"Get the contacts from a MeinVz account",
	'base_version'=>'1.6.5',
	'type'=>'social',
	'check_url'=>'http://www.meinvz.net/'
	);
/**
 * MeinVz Plugin
 * 
 * Import user's contacts from MeinVz and send 
 * messages using the internal messaging system
 * 
 * @author OpenInviter
 * @version 1.0.0
 */
class meinvz extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='email';
	public $internalError=false;
	public $allowed_domains=false;
	
	public $debug_array=array(
				'initial_get'=>'password',
				'login_post'=>'Friends',
				'url_friends'=>'name',
				'url_send_message'=>'recipientIdForHistory',
				'send_message'=>'SendSuccessAd'
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
		$this->service='meinvz';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
		$res=$this->get("http://www.meinvz.net/");
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"http://www.meinvz.com/",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://www.meinvz.com/",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
			
		$form_action="https://secure.meinvz.net/Login ";
		$post_elements=$this->getHiddenElements($res);$post_elements['email']=$user;$post_elements['password']=$pass;$post_elements['login']='Login';$post_elements['jsEnabled']='true';
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
		$url_logout='http://www.meinvz.net/Logout/'.$this->getElementString($res,'<li><a href="/Logout/','"');
		$url_friends='http://www.meinvz.net/Friends/'.$this->getElementString($res,'/Friends/','"');
		$this->login_ok=$url_friends;
		file_put_contents($this->getLogoutPath(),$url_logout);
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
		$res=$this->get($url,true);
		if ($this->checkResponse("url_friends",$res))
			$this->updateDebugBuffer('url_friends',$url,'GET');
		else
			{
			$this->updateDebugBuffer('url_friends',$url,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$contacts=array();
		$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
		$xpath=new DOMXPath($doc);$query="//dd[@class='name']";$data=$xpath->query($query);
		foreach ($data as $node)
			{
			$childs_node=$node->childNodes;
			foreach($childs_node as $child_node)
				if ($child_node->nodeName=='a')
					if (strpos($child_node->getAttribute('href'),'Profile')!==false) 
						$contacts[str_replace('/Profile/','/Messages/WriteMessage/',(string)$child_node->getAttribute('href'))]=trim($child_node->nodeValue);
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
			$url_send_message="http://www.meinvz.net{$href}";
			$res=$this->get($url_send_message,true);
			if ($this->checkResponse("url_send_message",$res))
				$this->updateDebugBuffer('url_send_message',"shttp://www.bebo.com/",'GET');
			else
				{
				$this->updateDebugBuffer('url_send_message',"http://www.bebo.com/",'GET',false);
				$this->debugRequest();
				$this->stopPlugin();
				return false;
				}
			$form_action=$url_send_message;
			$formkey_array=$this->getElementDOM($res,"//input[@name='formkey']",'value');
			$checkcode_array=$this->getElementDOM($res,"//input[@name='checkcode']",'value');
			$iv_array=$this->getElementDOM($res,"//input[@name='iv']",'value');
			$post_elements=array('recipientIds[]'=>$this->getElementString($res,'friendList" value="{&#34;','&#34;'),
								 'subject'=>$message['subject'],
								 'message'=>$message['body'],
								 'recipientIdForHistory'=>$this->getElementString($res,'friendList" value="{&#34;','&#34;'),
								 'formkey'=>$formkey_array[1],
								 'iv'=>$iv_array[1],
								 'checkcode'=>$checkcode_array[1],
								);
			$res=$this->post($form_action,$post_elements,true);
			if ($this->checkResponse("send_message",$res))
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
		if (file_exists($this->getLogoutPath()))
			{
			$url_logout=file_get_contents($this->getLogoutPath());
			$res=$this->get($url_logout,true);
			}
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	}	

?>