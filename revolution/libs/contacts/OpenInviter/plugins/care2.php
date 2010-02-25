<?php
$_pluginInfo=array(
	'name'=>'Care2',
	'version'=>'1.0.0',
	'description'=>"Get the contacts from a Care2 account",
	'base_version'=>'1.6.5',
	'type'=>'email',
	'check_url'=>'http://passport.care2.net/login.html?promoID=1'
	);
/**
 * Care2 Plugin
 * 
 * Import user's contacts from Care2 account
 *
 * 
 * @author OpenInviter
 * @version 1.0.0
 */
class care2 extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='email';
	public $internalError=false;
	public $allowed_domains=false;
	
	public $debug_array=array(
				'initial_get'=>'loginemail',
				'login_post'=>'navbar-email',
				'url_home'=>'self.location.replace',
				'file_contacts'=>'Name',
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
		$this->service='care2';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;

		$res=$this->get("http://passport.care2.net/login.html?promoID=1");
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"http://us.cyworld.com/",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://us.cyworld.com/",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
			
		$form_action="http://passport.care2.net/login.html?promoID=1";
		$post_elements=array('promoID'=>1,
							'loginemail'=>$user,
							'password'=>$pass,
							'perm'=>'on',
							'login'=>'Log In'
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
			
		$res=$this->get('http://www.care2.com/mail.html',true);
		if ($this->checkResponse("url_home",$res))
			$this->updateDebugBuffer('url_home',"http://www.care2.com/mail.html",'GET');
		else
			{
			$this->updateDebugBuffer('url_home',"http://www.care2.com/mail.html",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$url_file_contacts='http://mail.care2.com/contacts/contacts_import_export.asp?action=export&app=Outlook_2007&NewContacts=true&ContactType=all';
		$this->login_ok=$url_file_contacts;
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
		$res=$this->get($url);
		if ($this->checkResponse("file_contacts",$res))
			$this->updateDebugBuffer('file_contacts',$url,'GET');
		else
			{
			$this->updateDebugBuffer('file_contacts',$url,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		
		$contacts=array();
		$temp=$this->parseCSV($res);
		$contacts=array();
		foreach ($temp as $values)
			{
			$name=$values[0].(empty($values[1])?'':(empty($values[0])?'':'-')."{$values[1]}");
			if (!empty($values[2]))
				$contacts[$values[2]]=(empty($name)?$values[2]:$name);
			if (!empty($values[3]))
				$contacts[$values[3]]=(empty($name)?$values[3]:$name);
			
			}
		foreach ($contacts as $email=>$name) if (!$this->isEmail($email)) unset($contacts[$email]);
		return $contacts;
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
		$res=$this->get("http://mail.care2.com/s/care2/logout.asp",true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	}	

?>