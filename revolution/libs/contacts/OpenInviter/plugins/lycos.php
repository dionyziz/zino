<?php
$_pluginInfo=array(
	'name'=>'Lycos',
	'version'=>'1.1.1',
	'description'=>"Get the contacts from a Lycos account",
	'base_version'=>'1.6.3',
	'type'=>'email',
	'check_url'=>'http://lycos.com'
	);
/**
 * Lycos Plugin
 * 
 * Import user's contacts from Lycos' AddressBook
 * 
 * @author OpenInviter
 * @version 1.0.9
 */
class lycos extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='user';
	public $internalError=false;
	public $allowed_domains=false;
	
	public $debug_array=array(
				'initial_get'=>'m_U',
				'login'=>'utm_source',
				'export_url'=>'csv',
				'file_contacts'=>'First Name'
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
		$this->service='lycos';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->timeout=30;
		if (!$this->init()) return false;
		
		$res=$this->get("http://lycos.com/",true);
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"http://lycos.com/",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://lycos.com/",'GET',false);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$get_elements=$this->getHiddenElements($res);$get_elements["m_U"]=$user;$get_elements["m_P"]=$pass;
		$url_login="http://registration.lycos.com/login.php?".http_build_query($get_elements);
		$res=$this->get($url_login,true);		
		
		if ($this->checkResponse("login",$res))
			$this->updateDebugBuffer('login',"http://registration.lycos.com/login.php?",'GET',true,$get_elements);
		else
			{
			$this->updateDebugBuffer('login',"http://registration.lycos.com/login.php?",'GET',false,$get_elements);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$url_export="http://mail.lycos.com/lycos/addrbook/ExportAddr.lycos?ptype=act&fileType=OUTLOOK";
		
		$this->login_ok=$url_export;
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
		$post_elements=array('ftype'=>'OUTLOOK');
		$res=$this->post($url,$post_elements);
		if ($this->checkResponse("file_contacts",$res))
			{
			$temp=$this->parseCSV($res);		
			$contacts=array();
			foreach ($temp as $values)
				{
				$name=$values[0].(empty($values[1])?'':(empty($values[0])?'':'-')."{$values[1]}").(empty($values[3])?'':" \"{$values[3]}\"").(empty($values[2])?'':' '.$values[2]);
				if (!empty($values[4]))
					$contacts[$values[4]]=(empty($name)?$values[4]:$name);
				}		
			$this->updateDebugBuffer('file_contacts',"{$url}",'GET');
			}
		else
			{
			$this->updateDebugBuffer('file_contacts',"{$url}",'GET',false);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
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
		$res=$this->get("https://registration.lycos.com/logout.php",true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	
	}	

?>