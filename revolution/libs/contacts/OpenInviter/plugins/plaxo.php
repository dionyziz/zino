<?php
$_pluginInfo=array(
	'name'=>'Plaxo',
	'version'=>'1.0.2',
	'description'=>"Get the contacts from a plaxo account",
	'base_version'=>'1.6.3',
	'type'=>'social',
	'check_url'=>'http://m.plaxo.com'
	);
/**
 * plaxo.com Plugin
 * 
 * Imports user's contacts from plaxo.com's AddressBook
 * 
 * @author OpenInviter
 * @version 1.4.7
 */
class plaxo extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='email';
	public $allowed_domains=false;
	public $debug_array=array(
			 'initial_check'=>'Welcome',
	    	);
	
	/**
	 * Login function
	 * 
	 * Makes all the necessary requests to authenticate
	 * the current user to the server.
	 * 
	 * @param string $user The current user.c
	 * @param string $pass The password for the current user.
	 * @return bool TRUE if the current user was authenticated successfully, FALSE otherwise.
	 */
	public function login($user,$pass)
		{
		$this->resetDebugger();
		$this->service='plaxo';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		$postAction = "http://m.plaxo.com/index.php?page=login";
		$postElem['email']=$user;
		$postElem['password']=$pass;
		$res = $this->post($postAction, $postElem, true);
		if ($this->checkResponse("initial_check",$res))
			$this->updateDebugBuffer('initial_check',$postAction,'POST');		
		else
			{
			$this->updateDebugBuffer('initial_check',$postAction,'POST',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$this->login_ok = "http://m.plaxo.com/?page=contacts";
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
		$contacts = array();
//Thanks to ROBOV99 for the pagination code
        $boolContinue = true; 
        $inti =1;
        while($boolContinue)
        {
		    $res=$this->get($url,true);
		    $res = $this->getElementString($res, "</p>", "</div>");
		    $res.="//exit//";
            $iCntThisPage =0;
		    while(stripos($res,'<a href="') !== false)
			    {
			    $res = $this->getElementString($res, '<a href="','//exit//');
			    $mail = $this->getElementString($res, '&amp;id=','">');
			    $contactstemp[$mail] = $this->getElementString($res,'">','</a>');
                if (strlen($mail) >2) $iCntThisPage++;
			    $res.="//exit//";
			    }
        if (0== $iCntThisPage) break;
        $strBegin = "http://m.plaxo.com/?page=contacts&pageNum=";
        $inti = $inti + 1;
        $url = $strBegin.$inti;
    }
    
    foreach ($contactstemp as $id=>$name)
    {
        $res = $this->get("http://m.plaxo.com/?page=contact&id=".$id,true);
        $mail = $this->getElementString($res,'mailto:','"');
        unset($contacts[$id]);
        $contacts[$mail] = !empty($name)?$name:false;
    }
    foreach ($contacts as $email=>$name) 
    { if (!$this->isEmail($email)) unset($contacts[$email]); }

	return $contacts;
}
	
	/**
	 * Terminate session
	 * 
	 * Terminates the current user's session,
	 * debugs the request and reset's the internal 
	 * debugger.
	 * 
	 * @return bool TRUE if the session was terminated successfully, FALSE otherwise.
	 */	
	public function logout()
		{
		if (!$this->checkSession()) return false;
		$res = $this->get('http://m.plaxo.com/index.php?page=logout', true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}
				
	}
?>