<?php
$_pluginInfo=array(
	'name'=>'Terra',
	'version'=>'1.0.0',
	'description'=>"Get the contacts from an Terra account",
	'base_version'=>'1.6.3',
	'type'=>'email',
	'check_url'=>'http://correo.terra.com/'
	);
/**
 * Terra Plugin
 * 
 * Imports user's contacts from Terra.com
 * 
 * @author OpenInviter
 * @version 1.0.0
 */
class terra extends OpenInviter_base
{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='user';
	public $internalError=false;
	public $allowed_domains=false;
	
	public $debug_array=array('initial_get'=>'pop3host',
			  				  'post_login'=>'location.href',
			  				  'file_contacts'=>'Email'
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
	public function login($user, $pass)
		{
		$this->resetDebugger();
		$this->service='terra';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
		$res=$this->get("http://correo.terra.com/");
		if ($this->checkResponse('initial_get',$res))
			$this->updateDebugBuffer('initial_get',"http://correo.terra.com/",'GET');
		else 
			{
			$this->updateDebugBuffer('initial_get',"http://correo.terra.com/",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}
		
		$form_action="http://correo.terra.com/atmail.php";
		$post_elements=array('pop3host'=>'terra.com','username'=>$user,'password'=>$pass,'LoginType'=>'xp');
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
		
		$url_file_contacts="http://correo.terra.com/abook.php?func=export&abookview=personal";
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
			$name=$values[6].(empty($values[17])?'':(empty($values[6])?'':'-')."{$values[17]}").(empty($values[18])?'':" \"{$values[18]}\"").(empty($values[19])?'':' '.$values[19]);
			if (!empty($values[1]))
				$contacts[$values[1]]=(empty($name)?$values[1]:$name);
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
		$res=$this->get("http://correo.terra.com/util.php?func=logout",true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		}
	}
?>