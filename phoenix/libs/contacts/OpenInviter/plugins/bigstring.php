<?php
$_pluginInfo=array(
	'name'=>'Bigstring',
	'version'=>'1.0.0',
	'description'=>"Get the contacts from an Bigstring account",
	'base_version'=>'1.6.5',
	'type'=>'email',
	'check_url'=>'http://www.bigstring.com/?old=1'
	);
/**
 * Bigstring Plugin
 * 
 * Imports user's contacts from Bigstring AddressBook
 * 
 * @author OpenInviter
 * @version 1.0.0
 */
class bigstring extends OpenInviter_base
{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='email';
	public $internalError=false;
	public $allowed_domains=array('bigstring.com');
	
	public $debug_array=array('initial_get'=>'userpass',
			  				  'login_post'=>'frame',
			  				  'url_contacts'=>'E-mail:'
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
		$this->service='bigstring';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
		$res = $this->get("http://www.bigstring.com/?old=1");
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"http://www.bigstring.com/?old=1",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://www.bigstring.com/?old=1",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
			
		$form_action='http://www.bigstring.com/email/login.php';
		$post_elements=array('username'=>$user,'userpass'=>$pass,'free'=>'Log-In'); 
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
		
		
			
		$url_contacts="http://www.bigstring.com/email/addressbook/viewallcontacts.php?view=detailed";
		$this->login_ok=$url_contacts;
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
		if ($this->checkResponse("url_contacts",$res))
			$this->updateDebugBuffer('url_contacts',$url,'GET');
		else
			{
			$this->updateDebugBuffer('url_contacts',$url,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
			
		$contacts=array();
		$names_array=$this->getElementDOM($res,"//td[@colspan='3']",'title');
		$emails_array=$this->getElementDOM($res,"//td[.='E-mail:']",'title');
		foreach($names_array as $key=>$values) $contacts[$emails_array[$key]]=$values;
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
		$res=$this->get("http://www.bigstring.com/email/logout.php",true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		}
	}
?>