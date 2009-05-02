<?php
$_pluginInfo=array(
	'name'=>'Live/Hotmail',
	'version'=>'1.5.2',
	'description'=>"Get the contacts from a Windows Live/Hotmail account",
	'base_version'=>'1.6.7',
	'type'=>'email',
	'check_url'=>'http://mail.live.com'
	);
/**
 * Live/Hotmail Plugin
 * 
 * Imports user's contacts from Windows Live's AddressBook
 * 
 * @author OpenInviter
 * @version 1.4.4
 */
class hotmail extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='email';
	public $internalError=false;
	protected $timeout=30;
	public $allowed_domains=array('hotmail','live','msn','chaishop');
	
	public $debug_array=array(
				'initial_get'=>'srf_uPost=',
				'post_login'=>'function OnBack()',
				'url_home'=>'TodayLight',
				'url_send_message'=>'000000000001;',
				'get_contacts'=>'contacts'
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
	function login($user,$pass)
		{
		$this->resetDebugger();
		$this->service='hotmail';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;		
		$res=$this->get("http://www.mail.live.com",true);

		if ($this->checkResponse('initial_get',$res))
			$this->updateDebugBuffer('initial_get',"http://www.mail.live.com",'GET');
		else 
			{
			$this->updateDebugBuffer('initial_get',"http://www.mail.live.com",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}
		$post_action=$this->getElementString($res,"srf_uPost='","'");
		$post_elements=array("idsbho"=>1,
							 "LoginOptions"=>2,
							 "CS"=>'',
							 "FedState"=>'',
							 "PPSX"=>$this->getElementString($res,"srf_sRBlob='","'"),
							 "type"=>11,
							 "login"=>$user,
							 "passwd"=>$pass,
							 "remMe"=>1,
							 "NewUser"=>0,
							 "PPFT"=>$this->getElementString($res,'value="','"'),
							 "i1"=>0,
							 "i2"=>2,							 
							);
		$res=$this->post($post_action,$post_elements,true);
		if (strpos($res,"DoSubmit()")!==false)
			{
			$form_action=$this->getElementString($res,'action="','"');
			$post_elements=array('wa'=>'wsignin1.0');
			$res=$this->post($form_action,$post_elements,true);	
			}
		if ($this->checkResponse('post_login',$res))
			$this->updateDebugBuffer('post_login',"{$post_action}",'POST',true,$post_elements);
		else 
			{
			$this->updateDebugBuffer('post_login',"{$post_action}",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}
		$res=$this->get("http://mail.live.com/",false,true,false);
		if ($this->checkResponse('url_home',$res))
			$this->updateDebugBuffer('url_home',"http://www.mail.live.com",'GET');
		else 
			{
			$this->updateDebugBuffer('url_home',"http://www.mail.live.com",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}

		$url_mobile_compose='http://mobile.live.com/hm/c.aspx?rru=folder.aspx%3ffolder%3d00000000-0000-0000-0000-000000000001';
		$this->login_ok=$url_mobile_compose;
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
		if ($this->checkResponse('url_send_message',$res))
			$this->updateDebugBuffer('url_send_message',$url,'GET');
		else 
			{
			$this->updateDebugBuffer('url_send_message',$url,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}
			
		$id=$this->getElementString($res,'000000000001;','"');
		$page=0;$contacts_per_page=40;$contacts_found=true;$contacts=array();
		while ($contacts_found)
			{
			$contacts_showed=$page*$contacts_per_page;$page++;$contacts_found=false;
			$url_contacts="http://mobile.live.com/hm/contacts.aspx?bf=0&ts=1&c=to&cf=0%3bfolder.aspx%3ffolder%3d00000000-0000-0000-0000-000000000001;{$id}&i={$contacts_showed}";
			$res=$this->get($url_contacts,true);
			if ($this->checkResponse('get_contacts',$res))
				$this->updateDebugBuffer('get_contacts',$url,'GET');
			else 
				{
				$this->updateDebugBuffer('get_contacts',$url,'GET',false);
				$this->debugRequest();
				$this->stopPlugin();
				return false;	
				}				
			$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
			$xpath=new DOMXPath($doc);$query="//img[@src='/content/images/hm/Contact.aimg']";$data=$xpath->query($query);
			foreach($data as $node)
				{
				$name=trim(utf8_decode((string)$node->nextSibling->nodeValue));			
				$email=trim(str_replace('(','',str_replace(')','',(string)$node->nextSibling->nextSibling->nextSibling->nodeValue)));
				if (isset($email)) $contacts[$email]=(isset($name)?$name:$email);
				$contacts_found=true;
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
		$res=$this->get('http://mobile.live.com/wml/signout.aspx',true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}
		
	}
?>