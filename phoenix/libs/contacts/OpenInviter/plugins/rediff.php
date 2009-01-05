<?php
$_pluginInfo=array(
	'name'=>'Rediff',
	'version'=>'1.1.6',
	'description'=>"Get the contacts from a Rediff account",
	'base_version'=>'1.6.3',
	'type'=>'email',
	'check_url'=>'http://mail.rediff.com'
	);
/**
 * Rediff Plugin
 * 
 * Import user's contacts from Rediff's AddressBook
 * 
 * @author OpenInviter
 * @version 1.1.6
 */
class rediff extends OpenInviter_Base
{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='user';
	public $allowed_domains=false;
	private $sess_id, $username, $siteAddr;
	public $debug_array=array(
			  'login_post'=>'If you are seeing this page, your browser settings prevent you from automatically redirecting to a new URL',
			  'go_to_inbox'=>'Write Mail',
			  'compose_mail'=>'Group Contacts'
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
		$this->service='rediff';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		$post_elements=array("login"=>"{$user}",
							"passwd"=>"{$pass}",
							"FormName"=>"existing");
		$res=htmlentities($this->post("http://mail.rediff.com/cgi-bin/login.cgi",$post_elements,true));
		if ($this->checkResponse("login_post",$res))
			{
				$this->updateDebugBuffer('login_post',"http://mail.rediff.com/cgi-bin/login.cgi",'POST',true,$post_elements);
				$link_to_extract = $this->getElementString($res, 'window.location.replace(&quot;', '&quot;);');
				$this->siteAddr = $this->getElementString($link_to_extract,'http://','/');
				$this->username = $user;
				$this->sess_id = $this->getElementString($link_to_extract,'&amp;session_id=','&amp;');					
				$url_redirect = "http://{$this->siteAddr}/bn/toggle.cgi?flipval=1&login={$this->username}&session_id={$this->sess_id}&folder=Inbox&formname=sh_folder&user_size=1";
			}
		else
		{
			$this->updateDebugBuffer('login_post',"http://mail.rediff.com/cgi-bin/login.cgi",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
		}
		$res = ($this->get($url_redirect, true, true));
		$url_redirect = ($this->getElementString($res,'click <a href=','>'));
		if (!$url_redirect)
			{
				$this->updateDebugBuffer('login_post->go_to_inbox',"http://mail.rediff.com/cgi-bin/login.cgi",'GET',false,$post_elements);
				$this->debugRequest();
				$this->stopPlugin();
				return false;			
			}
		$url_redirect = "http://".$this->siteAddr.$url_redirect;
		$res = ($this->get($url_redirect, true, true));
		if ($this->checkResponse("go_to_inbox",$res))
			$this->updateDebugBuffer('go_to_inbox',$url_redirect,'GET');		
		else
			{
			$this->updateDebugBuffer('go_to_inbox',$url_redirect,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$url_contact = "http://{$this->siteAddr}/bn/address.cgi?login={$this->username}&session_id={$this->sess_id}&FormName=popup_list&field=to";
		$last = $url_contact;
		$res = $this->get($url_contact,true,true);
		if ($this->checkResponse("compose_mail",$res))
			{
			$this->login_ok = $url_contact;
			$logout_url = "http://login.rediff.com/bn/logout.cgi?formname=general&login={$this->username}&session_id={$this->sess_id}&function_name=logout";
			file_put_contents($this->getLogoutPath(),$logout_url);
			$this->updateDebugBuffer('compose_mail',$last,'GET');
			return true;
			}
		else 
			{
			$this->updateDebugBuffer('compose_mail',$last,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
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
			//parse the contacts list
			if ($this->login_ok)
				{
				$contactsarr = array();
				$res = $this->get($this->login_ok,true,true);
				$res .= "||exit||";
				while(strstr($res, '<input type="checkbox"'))
				{	
					$res = $this->getElementString($res, '<input type="checkbox" name="to" value=','||exit||');
					$res .= "||exit||";
					$contact_mail = $this->getElementString($res, '"','"');
					$res = $this->getElementString($res, '" ','||exit||');
					$res .= "||exit||";
					$contact_nick = $this->getElementString($res,'>','<br>');
					if (strpos($contact_mail, ",") === false)
					{
						$contactsarr[$contact_mail] = $contact_nick;
					}			
				}
				foreach ($contactsarr as $email=>$name) if (!$this->isEmail($email)) unset($contactsarr[$email]);
				return $contactsarr;
				}
			else
				{
				$this->debugRequest();
				$this->stopPlugin();
				return false;
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
			if (!empty($url_logout)) $res=$this->get($url_logout);
			}
			$this->debugRequest();
			$this->resetDebugger();
			$this->stopPlugin();	
		}
}
?>