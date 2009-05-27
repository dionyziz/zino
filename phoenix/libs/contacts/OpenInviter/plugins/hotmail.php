<?php
$_pluginInfo=array(
	'name'=>'Live/Hotmail',
	'version'=>'1.5.5',
	'description'=>"Get the contacts from a Windows Live/Hotmail account",
	'base_version'=>'1.6.9',
	'type'=>'email',
	'check_url'=>'http://home.mobile.live.com/'
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
	protected $userAgent='Mozilla/4.1 (compatible; MSIE 5.0; Symbian OS; Nokia 3650;424) Opera 6.10  [en]';
	public $allowed_domains=false;
	
	public $debug_array=array(
				'initial_get'=>'c_signin',
				'url_login'=>'signup.live',
				'post_login'=>'function OnBack()',
				'url_people'=>'SecondaryText',
				'get_contacts'=>'BoldText'
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
		$res=$this->get("http://home.mobile.live.com/",true);
		if ($this->checkResponse('initial_get',$res))
			$this->updateDebugBuffer('initial_get',"http://home.mobile.live.com/",'GET');
		else 
			{
			$this->updateDebugBuffer('initial_get',"http://home.mobile.live.com/",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}
		
		$url_login=html_entity_decode($this->getElementString($res,'id="c_signin" href="','"'));
		$res=$this->get($url_login,true);
		if ($this->checkResponse('url_login',$res))
			$this->updateDebugBuffer('url_login',$url_login,'GET');
		else 
			{
			$this->updateDebugBuffer('url_login',$url_login,'GET',false);
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

		$url_mobile='http://mpeople.live.com/default.aspx?pg=0';
		$this->login_ok=$url_mobile;
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
		if ($this->checkResponse('url_people',$res))
			$this->updateDebugBuffer('url_people',$url,'GET');
		else 
			{
			$this->updateDebugBuffer('url_people',$url,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}
			
		$maxNumberContacts_bulk=$this->getElementString($res,'id="lh" class="SecondaryText">','<');
		$maxNumberContacts_array=explode(" ",$maxNumberContacts_bulk);
		$maxNumberContacts = 0;
		foreach ($maxNumberContacts_array as $item) if (is_numeric(str_replace(')','',$item))) $maxNumberContacts = max(intval(str_replace(')','',$item)),$maxNumberContacts);
		
		if (empty($maxNumberContacts)) return array();
		$page=0;$contor=0;$contacts=array();
		while ($contor<=$maxNumberContacts)
			{
			$page++;
			$url_next="http://mpeople.live.com/default.aspx?pg={$page}";
			$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
			$xpath=new DOMXPath($doc);$query="//a";$data=$xpath->query($query);
			foreach($data as $node)
				{
				$identifier="";
				if (strpos($node->getAttribute('id'),'dnlk')!==false)
					{
					$contor++;
					$name=trim(utf8_decode((string)$node->nodeValue));
					$identifier=(int)str_replace('dnlk','',$node->getAttribute('id'));
					$href_node=$doc->getElementById("elk{$identifier}");
					if (isset($href_node)) 
						{	
						$email_bulk=$href_node->getAttribute('href');
						$email=urldecode($this->getElementString($email_bulk,'rru=compose&to=','&'));
						if (!empty($email)) $contacts[$email]=$name;	
						}
					else $contacts[$name]=$name;
					}			
				}
			$res=$this->get($url_next,true);
			if ($this->checkResponse('get_contacts',$res))
				$this->updateDebugBuffer('get_contacts',$url_next,'GET');
			else 
				{
				$this->updateDebugBuffer('get_contacts',$url_next,'GET',false);
				$this->debugRequest();
				$this->stopPlugin();
				return false;	
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
		$res=$this->get('http://mpeople.live.com/default.aspx?pg=0&PreviewScreenWidth=176',true);
		$url_logout=html_entity_decode($this->getElementString($res,'<a id="SignOutLink" href="','"'));
		$res=$this->get($url_logout,true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}
		
	}
?>
