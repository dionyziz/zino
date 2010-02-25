<?php
$_pluginInfo=array(
	'name'=>'Mail.ru',
	'version'=>'1.1.0',
	'description'=>"Get the contacts from a Mail.ru account",
	'base_version'=>'1.6.3',
	'type'=>'email',
	'check_url'=>'http://www.mail.ru'
	);
/**
 * Mail.ru Plugin
 * 
 * Import user's contacts from Mail.ru's AddressBook
 * 
 * @author OpenInviter
 * @version 1.0.9
 */
class mail_ru extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='email';
	public $internalError=false;
	protected $timeout=30;
	public $allowed_domains=array('list.ru','inbox.ru','bk.ru','mail.ru');
	
	public $debug_array=array(
				'initial_get'=>'login',
				'login_post'=>'mra_confirm',
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
		$this->service='mail_ru';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
		$res=$this->get("http://www.mail.ru/",true);
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"http://www.mail.ru/",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://www.mail.ru/",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
			
		$array_user=explode("@",$user);$domain=strtolower($array_user[1]);
		$hidden_element=$this->getElementDOM($res,"//input[@name='Mpopl']","value");
		$post_elements=array('Domain'=>$domain,'Login'=>$user,'Password'=>$pass,'Mpopl'=>$hidden_element[0]);
		$res=$this->post("http://win.mail.ru/cgi-bin/auth",$post_elements,true);
		if ($this->checkResponse("login_post",$res))
			$this->updateDebugBuffer('login_post',"http://win.mail.ru/cgi-bin/auth",'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login_post',"http://win.mail.ru/cgi-bin/auth",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$url_export="http://win.mail.ru/cgi-bin/abexport/addressbook.csv";
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
		$post_elements=array("confirm"=>"1","abtype"=>"1");
		$res=$this->post($url,$post_elements);
		
		if ($this->checkResponse("file_contacts",$res))
			{
			$temp=$this->parseCSV($res);
			$contacts=array();
			foreach ($temp as $values)
				{
				$name=$values[0].(empty($values[1])?'':(empty($values[0])?'':'-')."{$values[1]}").(empty($values[2])?'':" \"{$values[2]}\"").(empty($values[3])?'':' '.$values[3]);
				if (!empty($values[8]))
					$contacts[$values[8]]=(empty($name)?$values[8]:$name);
				if (!empty($values[9]))
					$contacts[$values[9]]=(empty($name)?$values[9]:$name);
				}
			$this->updateDebugBuffer('file_contacts',"{$url}",'POST',true,$post_elements);
			}
		else
			{
			$this->updateDebugBuffer('file_contacts',"{$url}",'POST',false,$post_elements);	
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
		$res=$this->get("http://win.mail.ru/cgi-bin/logout",true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	
	}	

?>