<?php
$_pluginInfo=array(
	'name'=>'Twitter',
	'version'=>'1.0.6',
	'description'=>"Get the contacts from a Twitter account",
	'base_version'=>'1.6.7',
	'type'=>'social',
	'check_url'=>'http://twitter.com'
	);
/**
 * Twitter Plugin
 * 
 * Imports user's contacts from Twitter and
 * posts a new tweet from the user as a invite.
 * 
 * @author OpenInviter
 * @version 1.0.3
 */
class twitter extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='user';
	public $internalError=false;
	public $allowed_domains=false;
	
	public $debug_array=array(
				'responce_ok'=>'screen_name',
				'responce_ok_followers'=>'screen_name',
				'responce_ok_status'=>'status',
				'url_direct_message'=>'direct_message'
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
		$this->service='twitter';
		$this->service_user=$user;
		$this->service_pass=$pass;
		if (!$this->init()) return false;
		$res=$this->get("http://{$user}:{$pass}@twitter.com/account/verify_credentials.xml",true);
		if ($this->checkResponse('responce_ok',$res))
			$this->updateDebugBuffer('responce_ok',"http://user:pass@twitter.com/account/verify_credentials.xml",'GET');
		else 
			{
			$this->updateDebugBuffer('responce_ok',"http://user:pass@twitter.com/account/verify_credentials.xml",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}
		file_put_contents($this->getLogoutPath(),"{$user}/{$pass}");
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
		if (file_exists($this->getLogoutPath())) 
			{$auth=explode("/",file_get_contents($this->getLogoutPath()));$user=$auth[0];$pass=$auth[1];}
		else return false;
		$res=$this->get("http://{$user}:{$pass}@twitter.com/statuses/followers.xml",true);
		if ($this->checkResponse('responce_ok_followers',$res))
			$this->updateDebugBuffer('responce_ok_followers',"http://user:pass@twitter.com/statuses/followers.xml",'GET');
		else 
			{
			$this->updateDebugBuffer('responce_ok_followers',"http://user:pass@twitter.com/statuses/followers.xml",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}
		$contacts=$this->getElementDOM($res,'//screen_name');
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
		if (file_exists($this->getLogoutPath())) 
			{$auth=explode("/",file_get_contents($this->getLogoutPath()));$user=$auth[0];$pass=$auth[1];}
		else return false;
		$post_elements=array('status'=>$message['body']);
		$res=$this->post("http://{$user}:{$pass}@twitter.com/statuses/update.xml",$post_elements,true);
		if ($this->checkResponse('responce_ok_status',$res))
			$this->updateDebugBuffer('responce_ok_status',"http://user:pass@twitter.com/statuses/update.xml",'POST',true,$post_elements);
		else 
			{
			$this->updateDebugBuffer('responce_ok_status',"http://user:pass@twitter.com/statuses/update.xml",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}
		foreach($contacts as $key=>$screen_name)
			{
			$post_elements=array('user'=>$screen_name,'text'=>$message['body']);
			$res=$this->post("http://{$user}:{$pass}@twitter.com/direct_messages/new.xml",$post_elements);
			if ($this->checkResponse('url_direct_message',$res))
				$this->updateDebugBuffer('url_direct_message',"http://user:pass@twitter.com/direct_messages/new.xml",'POST',true,$post_elements);
			else 
				{
				$this->updateDebugBuffer('url_direct_message',"http://user:pass@twitter.com/direct_messages/new.xml",'POST',false,$post_elements);
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
	 * 
	 */	
	public function logout()
		{
		if (!$this->checkSession()) return false;
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	}	

?>