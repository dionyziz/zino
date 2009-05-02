<?php
$_pluginInfo=array(
	'name'=>'IndiaTimes',
	'version'=>'1.0.3',
	'description'=>"Get the contacts from an IndiaTimes account",
	'base_version'=>'1.6.3',
	'type'=>'email',
	'check_url'=>'http://in.indiatimes.com/default1.cms'
	);
/**
 * IndiaTimes Plugin
 * 
 * Imports user's contacts from IndiaTimes' AddressBook
 * 
 * @author OpenInviter
 * @version 1.0.0
 */
class indiatimes extends OpenInviter_Base
{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='user';
	public $internalError=false;
	public $allowed_domains=false;
	
	public $debug_array=array('initial_get'=>'passwd',
			  				  'login_post'=>'Location',
			  				  'inbox_url'=>'sunsignid="2"',
			  				  'file_contacts'=>'email'
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
		$this->service='indiatimes';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
		$res = $this->get("http://in.indiatimes.com/default1.cms");
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"http://in.indiatimes.com/default1.cms",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://in.indiatimes.com/default1.cms",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
			
		$form_action=html_entity_decode($this->getElementString($res,'return checkVal(this);" action="','"'));
		$post_elements=array('login'=>$user,
							 'passwd'=>$pass,
							 'Sign in'=>'Sign In'
							); 
							
 		$res=$this->post($form_action,$post_elements,false,true,false,array(),false,false);
 		
 		if ($this->checkResponse("login_post",$res))
			$this->updateDebugBuffer('login_post',$form_action,'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login_post',$form_action,'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		
		$basepath=$this->getElementString($res,"Location: ",'jsp')."jsp";
		$res=$this->get($basepath,true);
		
		if ($this->checkResponse("inbox_url",$res))
			$this->updateDebugBuffer('inbox_url',$basepath,'GET');
		else
			{
			$this->updateDebugBuffer('inbox_url',$basepath,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
	
		$url_file_contacts=str_replace("/it/login.jsp","",$basepath)."/home/{$user}/Contacts.csv";
		
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
			
		$temp=$this->parseCSV($res);
		$contacts=array();
		foreach ($temp as $values)
			{
			$name=(!empty($values[4])?$values[4]:'').(empty($values[5])?'':(empty($values[4])?'':'-')."{$values[5]}").(empty($values[6])?'':' '.$values[6]);
			if (!empty($values[0]))
				$contacts[$values[0]]=(empty($name)?$values[0]:$name);
			if (!empty($values[1]))
				$contacts[$values[1]]=(empty($name)?$values[1]:$name);
			if (!empty($values[2]))
				$contacts[$values[2]]=(empty($name)?$values[2]:$name);
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
		$res=$this->get("http://mb.indiatimes.com/it/logout.jsp",true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		}
	}
?>