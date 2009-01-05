<?php
$_pluginInfo=array(
	'name'=>'Live/Hotmail',
	'version'=>'1.4.5',
	'description'=>"Get the contacts from a Windows Live/Hotmail account",
	'base_version'=>'1.6.3',
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
	public $allowed_domains=array('hotmail','live','msn','chaishop');
	
	public $debug_array=array(
				'initial_get'=>'srf_uPost=',
				'post_login'=>'function OnBack()',
				'url_print'=>'ContactsPrintPane',
				'get_contacts'=>'Title'
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
		$url_redirect=$this->getElementString($res,'Location: ','/TodayLight');
		$this->login_ok=$this->login_ok=$url_redirect;
		file_put_contents($this->getLogoutPath(),$url_redirect);
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
			$base_url=$this->login_ok;
		$contacts=array();
		$url_contacts=$base_url."/GetContacts.aspx?n=";		
		$res=$this->get($url_contacts,true);
		if (strpos($res,'MessageAtLoginForm')!==false)
			{
			$form_action=$base_url."/".$this->getElementString($res,'MessageAtLoginForm" method="post" action="','"');
			$post_elements=array('__VIEWSTATE'=>$this->getElementString($res,'id="__VIEWSTATE" value="','"'),
								'__EVENTVALIDATION'=>$this->getElementString($res,'id="__EVENTVALIDATION" value="','"'),
								'TakeMeToInbox'=>'Continue',
								);
			$res=$this->post($form_action,$post_elements,true);
			$res=$this->get($url_contacts,true);
			}
		if (strpos($res,'default.aspx?rru=contacts'))
			$res=$this->get("{$base_url}/default.aspx?rru=contacts",true);
		
		if ((empty($res)) OR ((strpos($res,'mt')!==false))) 
			{ 
			$res=$this->get("http://mail.live.com/default.aspx?wa=wsignin1.0",true);
			$url_print=$base_url."/PrintShell.aspx?type=contact&groupId=00000000-0000-0000-0000-000000000000";
			$res=$this->get($url_print,true);
			if ($this->checkResponse("url_print",$res))
				$this->updateDebugBuffer('url_print',$url_print,'GET');
			else
				{ 
				$this->updateDebugBuffer('url_print',$url_print,'GET',false);
				$this->debugRequest();
				$this->stopPlugin();
				return false;
				}
			$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
			$xpath=new DOMXPath($doc);$query="//div[@class='ContactsPrintPane cPrintContact BorderTop']";$data=$xpath->query($query);
			foreach($data as $node)
				{
				$temp=$node->childNodes->item( 4 );
				if (!empty($temp))
					{
					$nodes_name=$node->childNodes->item( 2 );$name=trim(preg_replace('/[^(\x20-\x7F)]*/','',(string)$nodes_name->nodeValue));
					$nodes_email=$temp;$brut_email=(string)$nodes_email->nodeValue;
					$array_email=explode(":",$brut_email);
					if (!empty($array_email[count($array_email)-1]))
						if (strpos($array_email[count($array_email)-1],'@')) $contacts[trim(preg_replace('/[^(\x20-\x7F)]*/','',$array_email[count($array_email)-1]))]=$name;
					}
				}  				
			}		
		elseif ((!empty($res) AND (strpos($res,'mt')===false)))
				{
				if ($this->checkResponse("get_contacts",$res))
					{
					$temp=explode(PHP_EOL,$res);
					unset($temp[0]);	
					$contacts=array();
					foreach ($temp as $temp_contact)
						{
						$contact_array=explode(',',str_replace(';',',',str_replace('"','',$temp_contact)));
						$name=(!empty($contact_array[1])?$contact_array[1]:'').(!empty($contact_array[2])?' '.$contact_array[2]:'').(!empty($contact_array[3])?' '.$contact_array[3]:'');
						if (!empty($contact_array[46]))
							$contacts[$contact_array[46]]=(empty($name)?$contact_array[46]:$name);
						if (!empty($contact_array[49]))
							$contacts[$contact_array[49]]=(empty($name)?$contact_array[49]:$name);
						if (!empty($contact_array[52]))
							$contacts[$contact_array[52]]=(empty($name)?$contact_array[52]:$name);
						}
					$this->updateDebugBuffer('get_contacts',$url_contacts,'GET');
					}
				else
					{ 
					$this->updateDebugBuffer('get_contacts',$url_contacts,'GET',false);
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
		if (file_exists($this->getLogoutPath()))
			{
			$url=file_get_contents($this->getLogoutPath());
			$url_logout=$url."/logout.aspx";
			$res=$this->get($url_logout,true);
			}
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}
		
	}
?>