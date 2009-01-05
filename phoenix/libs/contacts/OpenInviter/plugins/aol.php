<?php
$_pluginInfo=array(
	'name'=>'AOL',
	'version'=>'1.4.7',
	'description'=>"Get the contacts from an AOL account",
	'base_version'=>'1.6.3',
	'type'=>'email',
	'check_url'=>'http://webmail.aol.com'
	);
/**
 * AOL Plugin
 * 
 * Imports user's contacts from AOL's AddressBook
 * 
 * @author OpenInviter
 * @version 1.4.7
 */
class aol extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='user';
	public $allowed_domains=false;
		
	public $debug_array=array(
			 'initial_get'=>'logintabs',
	    	 'login_post'=>'loginForm',
	    	 'alternate_redirect_1'=>'loginForm',
	    	 'alternate_redirect_2'=>'gSuccessPath',
	    	 'standard_redirect'=>'gSuccessPath',
	    	 'inbox'=>'aol.wsl.afExternalRunAtLoad = []',
	    	 'print_contacts'=>'window\x27s'
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
		$this->service='aol';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
	
		$res=$this->get("http://webmail.aol.com",true);
		
		if ($this->checkResponse('initial_get',$res))
			$this->updateDebugBuffer('initial_get',"http://webmail.aol.com",'GET');
		else 
			{
			$this->updateDebugBuffer('initial_get',"http://webmail.aol.com",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}  
		$post_elements=$this->getHiddenElements($res);$post_elements['loginId']=$user;$post_elements['password']=$pass;
		$res=$this->post("https://my.screenname.aol.com/_cqr/login/login.psp",$post_elements,true);
		
		 
		if ($this->checkResponse('login_post',$res))	
			$this->updateDebugBuffer('login_post',"https://my.screenname.aol.com/_cqr/login/login.psp",'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login_post',"https://my.screenname.aol.com/_cqr/login/login.psp",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		
		
		$url_redirect=$this->getElementString($res,"('loginForm', 'false', '","')");
		if (!$url_redirect)
			{
			$this->updateDebugBuffer('login_post->standard_redirect',"https://my.screenname.aol.com/_cqr/login/login.psp",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
			
		$res=$this->get($url_redirect,true);
		
		
		if (strpos($res,"loginForm")!==false)
			{
			if ($this->checkResponse('alternate_redirect_1',$res))
				$this->updateDebugBuffer('alternate_redirect_1',"{$url_redirect}",'GET');
			else 
				{
				$this->updateDebugBuffer('alternate_redirect_1',"{$url_redirect}",'GET',false);
				$this->debugRequest();
				$this->stopPlugin();
				return false;	
				}
			$url_redirect=html_entity_decode($this->getElementString($res,"('loginForm', 'false', '","')"));
			$res=$this->get($url_redirect,true,true);
			
			 
			if ($this->checkResponse('alternate_redirect_2',$res)) 
				$this->updateDebugBuffer('alternate_redirect_2',"{$url_redirect}",'GET');
			else 
				{
				$this->updateDebugBuffer('alternate_redirect_2',"{$url_redirect}",'GET',false);
				$this->debugRequest();
				$this->stopPlugin();
				return false;	
				}
			$url_redirect="http://webmail.aol.com".htmlspecialchars_decode($this->getElementString($res,"var gSuccessPath = &quot;","&quot;",$url_redirect));
			$url_redirect=str_replace("Suite.aspx","Lite/Today.aspx",$url_redirect);
			$res=$this->get($url_redirect,true);
			}
		else
			{
			if ($this->checkResponse('standard_redirect',$res)) 	
				$this->updateDebugBuffer('standard_redirect',"{$url_redirect}",'GET');		
			else
				{ 
				$this->updateDebugBuffer('standard_redirect',"{$url_redirect}",'GET',false);
				$this->debugRequest();
				$this->stopPlugin();
				return false;	
				}
  
			$url_redirect="http://webmail.aol.com".htmlspecialchars_decode($this->getElementString($res,'var gSuccessPath = "','"',$url_redirect));
			$url_redirect=str_replace("Suite.aspx","Lite/Today.aspx",$url_redirect);
			$res=$this->get($url_redirect,true);
			}
		if ($this->checkResponse('inbox',$res))
			$this->updateDebugBuffer('inbox',"{$url_redirect}",'GET');
		else 
			{
			$this->updateDebugBuffer('inbox',"{$url_redirect}",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$url_contact=$this->getElementDOM($res,"//a[@id='contactsLnk']",'href');
		$this->login_ok=$this->login_ok=$url_contact[0];
		file_put_contents($this->getLogoutPath(),$url_contact[0]);
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
		$res=$this->get($url,true);

		
		$url_temp=$this->getElementString($res,"command.','','","'");
		$version=$this->getElementString($url_temp,'http://webmail.aol.com/','/');
		$url_print=str_replace("');","",str_replace("PrintContacts.aspx","addresslist-print.aspx?command=all&sort=FirstLastNick&sortDir=Ascending&nameFormat=FirstLastNick&version={$version}:webmail.aol.com&user=",$url_temp));
		$url_print.=$this->getElementString($res,"addresslist-print.aspx','","'");
		

	 	$res=$this->get($url_print,true);
	
		$contacts=array();
		if ($this->checkResponse("print_contacts",$res))
			{
			$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
			$nodes=$doc->getElementsByTagName("span");$name=false;$flag_name=false;$flag_email=false;
			foreach($nodes as $node) 
				{
				if ($flag_name) 
					if (!empty($node->nodeValue)) {$contacts[$node->nodeValue]=$name;$name=false;$flag_name=false;}
				if ($flag_email) 
					if (!empty($node->nodeValue)) {$contacts[$node->nodeValue]=$node->nodeValue;$name=false;$flag_email=false;}
				if ($node->getAttribute("class")=="fullName") if (!empty($node->nodeValue)) $name=$node->nodeValue;
				if ($name) {if ($node->nodeValue=="Email 1:") $flag_name=true;}
				elseif ($node->nodeValue=="Email 1:") $flag_email=true; 
				}
			$this->updateDebugBuffer('print_contacts',"{$url_print}",'GET');
			}
		else
			{ 
			$this->updateDebugBuffer('print_contacts',"{$url_print}",'GET',false);
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
		if (file_exists($this->getLogoutPath()))
			{
			$url=file_get_contents($this->getLogoutPath());
			$res=$this->get($url,true);
			$url_logout=$this->getElementDOM($res,"//a[@class='signOutLink']",'href');
			if (!empty($url_logout)) $res=$this->get($url_logout[0]);
			}
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}
				
	}
?>