<?php
/*This plugin import Gmail contacts
 *You can send normal email   
 */
$_pluginInfo=array(
	'name'=>'GMail',
	'version'=>'1.4.1',
	'description'=>"Get the contacts from a GMail account",
	'base_version'=>'1.6.3',
	'type'=>'email',
	'check_url'=>'http://google.com'
	);
/**
 * GMail Plugin
 * 
 * Imports user's contacts from GMail's AddressBook
 * 
 * @author OpenInviter
 * @version 1.4.1
 */
class gmail extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $internalError=false;
	public $requirement=false;
	public $allowed_domains=array('gmail','googlemail');
	
	public $debug_array=array(
	  'login_post'=>'Auth=',
	  'contact_xml'=>'entry'
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
		$this->service='gmail';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
			
		$post_elements=array('accountType'=>'GOOGLE','Email'=>$user,'Passwd'=>$pass,'service'=>'cp','source'=>'OpenInviter-OpenInviter-1.5.1');
	    $res=$this->post("https://www.google.com/accounts/ClientLogin",$post_elements,true);
	    if ($this->checkResponse("login_post",$res))
			$this->updateDebugBuffer('login_post',"https://www.google.com/accounts/ClientLogin",'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login_post',"https://www.google.com/accounts/ClientLogin",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
	    
		$auth=substr($res,strpos($res,'Auth=')+strlen('Auth='));
		
		$this->login_ok=$auth;
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
		if ($this->login_ok===false)
			{
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		else $auth=$this->login_ok; 
		$res=$this->get("http://www.google.com/m8/feeds/contacts/default/full?max-results=10000",true,false,true,false,array("Authorization"=>"GoogleLogin auth={$auth}"));
		if ($this->checkResponse("contact_xml",$res))
			$this->updateDebugBuffer('contact_xml','http://www.google.com/m8/feeds/contacts/default/full?max-results=10000','GET');
		else
			{
			$this->updateDebugBuffer('contact_xml','http://www.google.com/m8/feeds/contacts/default/full?max-results=10000','GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		
		$contacts=array();
		
		$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
		$xpath=new DOMXPath($doc);$query="//entry";$data=$xpath->query($query);
		foreach ($data as $node) 
			{
			$entry_nodes=$node->childNodes;	
			foreach($entry_nodes as $child)
				{ 
				if ($child->nodeName=='title') $name=$child->nodeValue;
				if (!empty($name))$contacts[$child->getAttribute('address')]=$name;
					else $contacts[$child->getAttribute('address')]=$child->getAttribute('address');
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
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}
	}
?>