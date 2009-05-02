<?php
/*Import Friends from Facebook
 * You can send message to your Friends Inbox
 */
$_pluginInfo=array(
	'name'=>'Facebook',
	'version'=>'1.0.9',
	'description'=>"Get the contacts from a Facebook account",
	'base_version'=>'1.6.3',
	'type'=>'social',
	'check_url'=>'http://www.facebook.com/'
	);
/**
 * FaceBook Plugin
 * 
 * Imports user's contacts from FaceBook and sends
 * messages using FaceBook's internal system.
 * 
 * @author OpenInviter
 * @version 1.0.8
 */
class facebook extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='email';
	public $internalError=false;
	public $allowed_domains=false;
	protected $timeout=30;
	
	public $debug_array=array(
				'initial_get'=>'pass',
				'login_post'=>'everyone',
				'friends'=>'return Friends.remove_click(',
				'url_message'=>'rand_id',
				'send_message'=>'window.location.replace'
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
		$this->service='facebook';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
		$res=$this->get("http://www.facebook.com/",true);
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"http://www.facebook.com/",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://www.facebook.com/",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		
		$form_action="https://login.facebook.com/login.php";
		$post_elements=array("email"=>$user,
							 "pass"=>$pass
						    );
		$res=$this->post($form_action,$post_elements,true,true);
		if ($this->checkResponse("login_post",$res))
			$this->updateDebugBuffer('login_post',"{$form_action}",'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login_post',"{$form_action}",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		
		$url_friends="http://www.facebook.com/friends/?everyone&ref=tn";

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
		
		$res=$this->get($url,true);
		if ($this->checkResponse("friends",$res))
			$this->updateDebugBuffer('friends',$url,'GET');
		else
			{
			$this->updateDebugBuffer('friends',$url,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		
		$contacts=array();$total=1;$page=0;	
		while ($total>0)
			{
			$total=0;$contacts_array=array();
			while(strpos($res,'return Friends.remove_click(')!==false)
				{
				$contacts_bulk=str_replace('&quot;',"",$this->getElementString($res,'return Friends.remove_click(',"&quot;, 0)"));
				if (!empty($contacts_bulk))
					{
					$contacts_array=explode(", ",$contacts_bulk);
					if (!empty($contacts_array[1])) {$contacts[$contacts_array[1]]=(!empty($contacts_array[2])?$contacts_array[2]:false);$total++;}
					}
				$res=substr($res,strpos($res,'return Friends.remove_click(')+strlen('return Friends.remove_click('),strlen($res));	
				}
			if ($total>0)
				{
				$page+=50;
				$res=$this->get("http://www.facebook.com/friends/?flid=0&view=everyone&q=&nt=0&nk=0&s={$page}&st=0");	
				}
			}
		return $contacts;
		}

	/**
	 * Send message to contacts
	 * 
	 * Sends a message to the contacts using
	 * the service's inernal messaging system
	 * 
	 * @param string $session_id The OpenInviter user's session ID
	 * @param string $message The message being sent to your contacts
	 * @param array $contacts An array of the contacts that will receive the message
	 * @return mixed FALSE on failure.
	 */
	public function sendMessage($session_id,$message,$contacts)
		{
		foreach ($contacts as $id=>$name)
			{
			$random_nr=rand(100000000,9000000);
			$url_send_message="http://www.facebook.com/inbox/?compose&id={$id}";
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
			if (strpos($res,'account being disabled')!==false) break;
			$form_action="http://www.facebook.com/inbox/";
			$post_elements=array("ids[]"=>$id,
								 'rand_id'=>$random_nr,
								 'subject'=>$message['subject'],
								 'message'=>$message['body'],				
								 "post_form_id"=>$this->getElementString($res,'post_form_id" value="','"'),
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
		$res=$this->get("http://www.facebook.com/home.php",true);
		if (!empty($res))
			{
			$url_logout="http://www.facebook.com/logout.php?h=".$this->getElementString($res,'http://www.facebook.com/logout.php?h=','"');
			$res=$this->get($url_logout,true);
			}
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	}	

?>