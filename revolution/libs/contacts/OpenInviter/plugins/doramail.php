<?php
$_pluginInfo=array(
	'name'=>'Doramail',
	'version'=>'1.0.1',
	'description'=>"Get the contacts from a Doramail account",
	'base_version'=>'1.6.5',
	'type'=>'email',
	'check_url'=>'http://www.doramail.com/scripts/common/index.main?signin=1&lang=us'
	);
/**
 * Doramail.com Plugin
 * 
 * Imports user's contacts from Doramail.com AddressBook
 * 
 * @author OpenInviter
 * @version 1.0.0
 */
class doramail extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $internalError=false;
	public $requirement='user';
	public $allowed_domains=false;
	protected $timeout=30;
	
	public $debug_array=array(
				'initial_get'=>'show_frame',
				'login_post'=>'frame',
				'url_inbox'=>'mailbox',
				'url_adressbook'=>'scripts',
				'url_export'=>'cgi',
				'export_file'=>'csv',
				'contacts_file'=>'Name'
				
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
		$this->service='doramail';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
					
		$res=$this->get("http://www.doramail.com/scripts/common/index.main?signin=1&lang=us");
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"http://www.doramail.com/scripts/common/index.main?signin=1&lang=us",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://www.doramail.com/scripts/common/index.main?signin=1&lang=us",'GET',false);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		
		$form_action="http://www.doramail.com/scripts/common/proxy.main";
		$post_elements=array('show_frame'=>'Enter',
							'action'=>'login',
							'domain'=>'doramail.com',
							'mail_language'=>'us',
							'longlogin'=>1,
							'login'=>$user,
							'password'=>$pass,
							 );
		$res=$this->post($form_action,$post_elements,true);
		if ($this->checkResponse('login_post',$res))
			$this->updateDebugBuffer('login_post',$form_action,'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login_post',$form_action,'POST',false,$post_elements);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		
		$url_frame_array=$this->getElementDOM($res,"//frame",'src');
		$res=$this->get($url_frame_array[1]);
		if ($this->checkResponse("url_inbox",$res))
			$this->updateDebugBuffer('url_inbox',"http://www.doramail.com/scripts/common/index.main?signin=1&lang=us",'GET');
		else
			{
			$this->updateDebugBuffer('url_inbox',"http://www.doramail.com/scripts/common/index.main?signin=1&lang=us",'GET',false);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		
		$url_inbox='http://mymail.doramail.com/scripts/mail/mailbox.mail?'.$this->getElementString($res,'/scripts/mail/mailbox.mail?','"');
		$res=$this->get($url_inbox);
		if ($this->checkResponse("url_adressbook",$res))
			$this->updateDebugBuffer('url_adressbook',"http://www.doramail.com/scripts/common/index.main?signin=1&lang=us",'GET');
		else
			{
			$this->updateDebugBuffer('url_adressbook',"http://www.doramail.com/scripts/common/index.main?signin=1&lang=us",'GET',false);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		
		$url_adressbook='http://mymail.doramail.com/scripts/addr/'.$this->getElementString($res,'/scripts/addr/','"');
		$this->login_ok=$url_adressbook;
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
		if ($this->checkResponse("url_export",$res))
			$this->updateDebugBuffer('url_export',$url,'GET');
		else
			{
			$this->updateDebugBuffer('url_export',$url,'GET',false);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		
		$url_export='http://mymail.doramail.com/scripts/addr/external.cgi?'.$this->getElementString($res,'http://mymail.doramail.com/scripts/addr/external.cgi?','"');
		$res=$this->get($url_export);
		if ($this->checkResponse("export_file",$res))
			$this->updateDebugBuffer('export_file',$url,'GET');
		else
			{
			$this->updateDebugBuffer('export_file',$url,'GET',false);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$form_action=$url_export;
		$post_elements=$this->getHiddenElements($res);$post_elements['format']='csv';
		$res=$this->post($form_action,$post_elements);
		if ($this->checkResponse("contacts_file",$res))
			$this->updateDebugBuffer('contacts_file',$form_action,'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('contacts_file',$form_action,'POST',false,$post_elements);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$temp=$this->parseCSV($res);	
		$contacts=array();
		foreach ($temp as $values)
			{
			$name=$values['0'].(empty($values['1'])?'':(empty($values['0'])?'':'-')."{$values['1']}").(empty($values['3'])?'':" \"{$values['3']}\"").(empty($values['2'])?'':' '.$values['2']);
			if (!empty($values['4']))
				$contacts[$values['4']]=(empty($name)?$values['4']:$name);
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
		$res=$this->get('http://mymail.doramail.com/scripts/mail/Outblaze.mail?logout=1&.noframe=1&a=1&',true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	
	}	

?>