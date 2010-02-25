<?php
/*This plugin import GMX.net contacts
 *You can send normal email   
 */
$_pluginInfo=array(
	'name'=>'GMX.net',
	'version'=>'1.0.6',
	'description'=>"Get the contacts from a GMX.net account",
	'base_version'=>'1.6.3',
	'type'=>'email',
	'check_url'=>'http://www.gmx.net'
	);
/**
 * GMX.net Plugin
 * 
 * Imports user's contacts from GMX.net's AddressBook
 * 
 * @author OpenInviter
 * @version 1.0.4
 */
class gmx_net extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $internalError=false;
	public $requirement='email';
	protected $timeout=30;
	public $allowed_domains=array('gmx.de','gmx.at','gmx.ch','gmx.net');
	
	public $debug_array=array(
				'initial_get'=>'uinguserid',
				'login'=>'Adressbuch',
				'export_file'=>'b_export',
				'contacts_file'=>'","'
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
		$this->service='gmx_net';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
					
		$res=$this->get("http://www.gmx.net/",true);
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('file_contacts',"http://www.gmx.net/",'GET');
		else
			{
			$this->updateDebugBuffer('file_contacts',"http://www.gmx.net/",'GET',false);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$form_action="http://service.gmx.net/de/cgi/login";
		$post_elements=array('AREA'=>1,
							'EXT'=>'redirect',
							'EXT2'=>'',
							'uinguserid'=>$this->getElementString($res,'name="uinguserid" value="','"'),
							'id'=>$user,
							'p'=>$pass,
							'browsersupported'=>true,
							'jsenabled'=>true
							 );
		$res=$this->post($form_action,$post_elements,true);
		if ($this->checkResponse("login",$res))
			$this->updateDebugBuffer('login',$form_action,'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login',$form_action,'POST',false,$post_elements);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$url_adress=str_replace("site=0","site=importexport","http://service.gmx.net/de/cgi/addrbk.fcgi?CUSTOMERNO=".html_entity_decode($this->getElementString($res,'http://service.gmx.net/de/cgi/addrbk.fcgi?CUSTOMERNO=','"')));
		$this->login_ok=$url_adress;
		file_put_contents($this->getLogoutPath(),$url_adress);
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
		$res=$this->get($url,true);
		if ($this->checkResponse("export_file",$res))
			$this->updateDebugBuffer('export_file',$url,'GET');
		else
			{
			$this->updateDebugBuffer('export_file',$url,'GET',false);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$form_action="http://service.gmx.net/de/cgi/addrbk.fcgi";
		$post_elements=$this->getHiddenElements($res);$post_elements['dataformat']='o2002';$post_elements['language']='english';$post_elements['b_export']='Export starten';
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
			$name=$values['1'].(empty($values['2'])?'':' '.$values['2']);
			if (!empty($values['28']))
				$contacts[$values['28']]=(empty($name)?$values['28']:$name);
			if (!empty($values['29']))
				$contacts[$values['29']]=(empty($name)?$values['29']:$name);
			if (!empty($values['30']))
				$contacts[$values['30']]=(empty($name)?$values['30']:$name);
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
		if (file_exists($this->getLogoutPath()))
			{
			$url=file_get_contents($this->getLogoutPath());
			$res=$this->get($url,true);		 
			$logout_url="https://service.gmx.net/de/cgi/nph-logout?CUSTOMERNO=".$this->getElementString($res,"https://service.gmx.net/de/cgi/nph-logout?CUSTOMERNO=",'"');
			$res=$this->get($logout_url,true);
			}
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	
	}	

?>