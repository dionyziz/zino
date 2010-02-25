<?php
$_pluginInfo=array(
	'name'=>'OperaMail',
	'version'=>'1.0.5',
	'description'=>"Get the contacts from an OperaMail account",
	'base_version'=>'1.6.0',
	'type'=>'email',
	'check_url'=>'http://www.operamail.com'
	);
/**
 * OperaMail Plugin
 * 
 * Import user's contacts from OperaMail
 * 
 * @author OpenInviter
 * @version 1.0.4
 */
class operamail extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='email';
	public $internalError=false;
	protected $timeout=30;
	public $allowed_domains=array('operamail');
	
	public $debug_array=array(
				'initial_get'=>'login',
				'login_post'=>'main?.ob',
				'file_contacts'=>'"'
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
		$this->service='operamail';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
		$res=$this->get("http://www.operamail.com/scripts/common/index.main?signin=1&lang=us",true);
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"http://www.operamail.com/scripts/common/index.main?signin=1&lang=us",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://www.operamail.com/scripts/common/index.main?signin=1&lang=us",'GET',false);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$form_action="http://www.operamail.com/scripts/common/proxy.main";
		$post_elements=$this->getHiddenElements($res);$post_elements['login']=$user;$post_elements['password']=$pass; 
		$res=$this->post($form_action,$post_elements,true);		
		if ($this->checkResponse("login_post",$res))
			$this->updateDebugBuffer('login_post',$form_action,'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login_post',$form_action,'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
			
		$url_file_contacts="http://mymail.operamail.com/scripts/addr/external.cgi?.ob=&gab=1";
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
		
		$form_action=$url;
		$post_elements=array('showexport'=>'showexport',
							 'action'=>'export',
							 'login'=>$this->service_user,
							 'format'=>'csv'
							 );
		$res=$this->post($form_action,$post_elements);
		
		if ($this->checkResponse("file_contacts",$res))
			$this->updateDebugBuffer('file_contacts',$form_action,'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('file_contacts',$form_action,'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$temp=$this->parseCSV($res);	
		$contacts=array();
		foreach ($temp as $values)
			{
			$name=$values[0].(empty($values[1])?'':(empty($values[0])?'':'-')."{$values[1]}").(empty($values[3])?'':" \"{$values[3]}\"").(empty($values[2])?'':' '.$values[2]);
			if (!empty($values[4]))
				$contacts[$values[4]]=(empty($name)?$values[4]:$name);
			if (!empty($values[12]))
				$contacts[$values[12]]=(empty($name)?$values[12]:$name);
			if (!empty($values[13]))
				$contacts[$values[13]]=(empty($name)?$values[13]:$name);
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
		$url_logout="http://mymail.operamail.com/scripts/mail/Outblaze.mail?logout=1&.noframe=1&a=1&";
		$res=$this->get($url_logout,true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}
	
	}	

?>