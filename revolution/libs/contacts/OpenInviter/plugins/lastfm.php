<?php
$_pluginInfo=array(
	'name'=>'Last.fm',
	'version'=>'1.0.0',
	'description'=>"Get the contacts from a Last.fm account",
	'base_version'=>'1.6.3',
	'type'=>'social',
	'check_url'=>'http://www.last.fm'
	);
/**
 * LastFm Plugin
 * 
 * Import user's contacts from Last.fm AddressBook
 * 
 * @author OpenInviter
 * @version 1.0.0
 */
class lastfm extends OpenInviter_Base
{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='user';
	public $allowed_domains=false;
	private $sess_id, $username, $siteAddr;
	public $debug_array=array(
			  'login_post'=>'<a href="/login/logout">',
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
		$this->service='lastfm';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		$post_elements=array("username"=>"{$user}",
							"password"=>"{$pass}",
							"backto"=>urldecode("http%3A%2F%2Fwww.last.fm%2Flogin%2FsuccessCallback"));
		$res=$this->post("https://www.last.fm/login?lang=&withsid",$post_elements,true);
		if ($this->checkResponse("login_post",$res))
			$this->updateDebugBuffer('login_post',"https://www.last.fm/login?lang=&withsid",'POST');		
		else
			{
			$this->updateDebugBuffer('login_post',"https://www.last.fm/login?lang=&withsid",'POST',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$this->login_ok = "http://www.last.fm/user/{$user}/friends";
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
			if ($this->login_ok)
				{
				$page = 1;
				$contacts = array();
				$flag = true;
				while($flag)
					{
					if ($page == 1)	$res = $this->get($this->login_ok);
					else			$res = $this->get($this->login_ok."?page={$page}");
					$res.= "||exit||";
					$token=$this->login_ok.'?page='.($page+1);
					if (strpos($res, $token)!==false){ $flag = true; $page++;}
					else $flag = false;
					while(strpos($res, 'id="r4_')!==false)
						{	
						$res = $this->getElementString($res, 'id="r4','||exit||');
						$res .= "||exit||";
						$id=$this->getElementString($res,'_','"');

						$contact_mail = $this->getElementString($res, '<a href="/user/','"');
						$contacts[$id] = $contact_mail;	
						}
					}
				return $contacts;
				}
			else
				{
				$this->debugRequest();
				$this->stopPlugin();
				return false;
				}
			
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
		$res = $this->get("http://www.last.fm/inbox/compose");
		$postelem = $this->getHiddenElements($res);
		$postelem['to']="";
		$postelem['subject']=$message['subject'];
		$postelem['body']=$message['body'];
		foreach ($contacts as $id => $username)
			{
			$postelem['to_ids%5B%5D'] = $id;
			$res = $this->post('http://www.last.fm/inbox/compose',$postelem, true, true);
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
		$logout_url = "http://www.last.fm/login/logout";
		$res = $this->get($logout_url);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}
}
?>