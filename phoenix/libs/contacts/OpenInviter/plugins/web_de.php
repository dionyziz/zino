<?php
$_pluginInfo=array(
	'name'=>'Web.de',
	'version'=>'1.0.0',
	'description'=>"Get the contacts from an web.de account",
	'base_version'=>'1.6.3',
	'type'=>'email',
	'check_url'=>'http://m.web.de'
	);
/**
 * web.de Plugin
 * 
 * Imports user's contacts from web.de's AddressBook
 * 
 * @author OpenInviter
 * @version 1.4.7
 */
class web_de extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='user';
	public $allowed_domains=false;
		
	public $debug_array=array(
			 'initial_check'=>'weiter',
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
		$this->service='web_de';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
	
		$res=$this->get("http://m.web.de/");
		$postElem = $this->getHiddenElements($res);
		$postAction = $this->getElementString($res,'action="','"');
		$postElem['user']=$user;
		$postElem['passw']=$pass;
		$postElem['sv-remove-name']='Login';
		$res = $this->post("http://m.web.de".$postAction, $postElem, true);
		if ($this->checkResponse("initial_check",$res))
			$this->updateDebugBuffer('initial_check',"http://m.web.de".$postAction,'POST');		
		else
			{
			$this->updateDebugBuffer('initial_check',"http://m.web.de".$postAction,'POST',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$redirectUrl = "http://m.web.de";
		$redirectUrl.=$this->getElementString($res,'href="','">weiter');
		$res=$this->get($redirectUrl,true);
		$logout_url = $this->getElementString($res,'Navigation</a>','Logout');
		$logout_url = "http://m.web.de".$this->getElementString($res,'<a href="','">');
		file_put_contents($this->getLogoutPath(),$logout_url);
		$L5 = $this->getElementString($res,'[5] <a href="','"');
		$L5 = "http://m.web.de".$L5;
		$this->login_ok = $L5;
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
		else
		$url=$this->login_ok;
		//go to url inbox
		$contacts = array();
		$res=$this->get($url,true);
		$res = $this->getElementString($res, '<div class="separator"><div><b>','<input type="hidden"');
		$i = 0;
		while (stripos($res, '<a href') !== false)
		{
			$i++;
			$res.="||exit||";
			$res=$this->getElementString($res,'a href="','||exit||');
			if ($i % 2 != 0)	$c_name = $this->getElementString($res, '">','</a>');
			else
				{ 
				$c_mail = trim($this->getElementString($res, '">','</a>'));
				$contacts[$c_mail] = $c_name;
				}
			
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
			 $url_logout=file_get_contents($this->getLogoutPath());		
			if (!empty($url_logout)) $res=$this->get($url_logout,true);
			}
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}
				
	}
?>