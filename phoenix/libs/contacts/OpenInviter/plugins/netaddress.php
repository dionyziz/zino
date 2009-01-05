<?php
$_pluginInfo=array(
	'name'=>'Netaddress',
	'version'=>'1.0.0',
	'description'=>"Get the contacts from a Netaddress account",
	'base_version'=>'1.6.5',
	'type'=>'email',
	'check_url'=>'https://www.netaddress.com/'
	);
/**
 * Mail_in Plugin
 * 
 * Imports user's contacts from Netaddress's AddressBook
 * 
 * @author OpenInviter
 * @version 1.0.0
 */
class netaddress extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='email';
	public $internalError=false;
	public $allowed_domains=false;
	
	public $debug_array=array('initial_get'=>'UserID',
							  'post_login'=>'Door',
							  'contacts_page'=>'fileformat',
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
	public function login($user, $pass)
	{
		$this->resetDebugger();
		$this->service='netaddress';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
		$res=$this->get("https://www.netaddress.com/");
		if ($this->checkResponse('initial_get',$res))
			$this->updateDebugBuffer('initial_get',"https://www.netaddress.com/",'GET');
		else 
			{
			$this->updateDebugBuffer('initial_get',"https://www.netaddress.com/",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}
			
		$form_action='https://www.netaddress.com/tpl/Door/LoginPost';
		$post_elements=array('UserID'=>$user,
							 'passwd'=>$pass,
							 'LoginState'=>2,
							 'SuccessfulLogin'=>'/tpl',
							 'NewServerName'=>'www.netaddress.com',
							 'JavaScript'=>'JavaScript1.2',
							 'DomainID'=>$this->getElementString($res,'"DomainID" value="','"'),
							 'Domain'=>$this->getElementString($res,'"Domain" value="','"')
							);
		$res=$this->post($form_action,$post_elements,true);
		$session_id=$this->getElementString($res,'/Door/','/');
		if ($this->checkResponse('post_login',$res))
			$this->updateDebugBuffer('post_login',"{$form_action}",'POST',true,$post_elements);
		else 
			{
			$this->updateDebugBuffer('post_login',"{$form_action}",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}
		$this->login_ok=$session_id;	
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
		else $id=$this->login_ok;
		$url_export="http://www.netaddress.com/icalphp/exportcontact.php?sid={$id}";
		$res=$this->get($url_export);
		if ($this->checkResponse('contacts_page',$res))
			$this->updateDebugBuffer('contacts_page',$url_export,'GET');
		else 
			{
			$this->updateDebugBuffer('contacts_page',$url_export,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}
		
		$form_action='http://www.netaddress.com/icalphp/exportcontact.php';
		$post_elements=array('sid'=>$id,'fileformat'=>'csv1','csv1charset'=>'UTF-8');
		$res=$this->post($form_action,$post_elements);
		if ($this->checkResponse('file_contacts',$res))
			$this->updateDebugBuffer('file_contacts',"{$form_action}",'POST',true,$post_elements);
		else 
			{
			$this->updateDebugBuffer('file_contacts',"{$form_action}",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}
		
		$temp=$this->parseCSV($res);
		$contacts=array();
		foreach ($temp as $values)
			{
			$name=$values[1].(empty($values[2])?'':(empty($values[1])?'':'-')."{$values[2]}").(empty($values[3])?'':" \"{$values[3]}\"");
			if (!empty($values[5]))
				$contacts[$values[5]]=(empty($name)?$values[5]:$name);
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
		$res=$this->get('http://mail.in.com/logout',true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();	
		}
}
?>
