<?php
$_pluginInfo=array(
	'name'=>'Gawab',
	'version'=>'1.0.1',
	'description'=>"Get the contacts from a Gawab account",
	'base_version'=>'1.6.5',
	'type'=>'email',
	'check_url'=>'http://www.gawab.com/default.php'
	);
/**
 * Gawab Plugin
 * 
 * Imports user's contacts from Gawab's AddressBook
 * 
 * @author OpenInviter
 * @version 1.0.0
 */
class gawab extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='email';
	public $internalError=false;
	public $allowed_domains=false;
	protected $timeout=30;
	
	public $debug_array=array(
				'initial_get'=>'service',
				'post_login'=>'&_host',
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
		$this->service='gawab';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
		$res=$this->get("http://www.gawab.com/default.php",true);
		if ($this->checkResponse('initial_get',$res))
			$this->updateDebugBuffer('initial_get',"http://www.gawab.com/default.php",'GET');
		else 
			{
			$this->updateDebugBuffer('initial_get',"http://www.gawab.com/default.php",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}
		$login_array=explode("@",$user);
		$form_action="http://mail.gawab.com/login";
		$post_elements=array('service'=>'webmail',
							 'username'=>$login_array[0],
							 'domain'=>$login_array[1],
							 'password'=>$pass
							 );
		$res=$this->post($form_action,$post_elements,true);
		if ($this->checkResponse('post_login',$res))
			$this->updateDebugBuffer('post_login',"{$form_action}",'POST',true,$post_elements);
		else 
			{
			$this->updateDebugBuffer('post_login',"{$form_action}",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}
		$host=$this->getElementString($res,'&_host=',"'");
		$url_file_contacts="http://mail.gawab.com/{$host}/gwebmail?_module=contact&_action=export&format=outlook&_address=dbautu@gawab.com";
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
		if ($this->checkResponse('file_contacts',$res))
			$this->updateDebugBuffer('file_contacts',$url,'GET');
		else 
			{
			$this->updateDebugBuffer('file_contacts',$url,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}
		
		
		$temp=$this->parseCSV($res);
		$contacts=array();
		foreach ($temp as $values)
			{
			$name=$values[0];
			if (!empty($values[1]))
				$contacts[$values[1]]=(empty($name)?$values[1]:$name);
			if (!empty($values[3]))
				$contacts[$values[3]]=(empty($name)?$values[3]:$name);
			if (!empty($values[4]))
				$contacts[$values[4]]=(empty($name)?$values[4]:$name);
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
		$res=$this->get("http://www.gawab.com/",true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}
	
	}	
?>