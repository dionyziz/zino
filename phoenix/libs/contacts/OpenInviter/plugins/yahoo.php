<?php
$_pluginName='Yahoo!';
$_pluginVersion='1.3.5';
$_pluginDescription="Get the contacts from a Yahoo! account";
$_requiredBaseVersion="1.5.0";
$_pluginType='email';
class yahoo extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	
	public $debug_array=array(
			  'initial_get'=>'form: login information',
			  'contacts_page'=>'import_export',
			  'export_post'=>'crumb2',
			  'contacts_file'=>'","'
			  );
			 
	public function login($user,$pass)
		{
		$this->resetDebugger();
		$this->service='yahoo';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->init();
		
		$res=$this->get("https://login.yahoo.com/config/mail?.intl=us&rl=1");
		
		if ($this->checkResponse('initial_get',$res))
			$this->updateDebugBuffer('initial_get',"https://login.yahoo.com/config/mail?.intl=us&rl=1",'GET');
		else 
			{
			$this->updateDebugBuffer('initial_get',"https://login.yahoo.com/config/mail?.intl=us&rl=1",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;
			}
		$post_elements=$this->getHiddenElements($res,'login',$user,'passwd',$pass);$post_elements["save"]="Sign+In";
		if (!$post_elements)
			{
			$this->updateDebugBuffer('login_post->contacts_page',"https://login.yahoo.com/config/login?",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;
			}
		$res=htmlentities($this->post("https://login.yahoo.com/config/login?",$post_elements,true));		
		$this->login_ok=$this->login_ok="http://address.mail.yahoo.com/";
		return true;
		}
		
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
		//get contacts url
		$contacts=array();
		$res=$this->get($url,true);
		if ($this->checkResponse("contacts_page",$res))
			{
			$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
			$xpath=new DOMXPath($doc);$query="//a[@href]";$data=$xpath->query($query);
			foreach($data as $val) 
				{
				if (strstr($val->getAttribute('href'),"import_export"))  	
					{$url_contacts="http://address.mail.yahoo.com/".str_replace("&amp;","&",$val->getAttribute('href'));break;}
				}
			$res=$this->get($url_contacts,true);
			$this->updateDebugBuffer('contacts_page',"http://address.mail.yahoo.com/",'GET');
			}
		else 
			{
			$this->updateDebugBuffer('contacts_page',"http://address.mail.yahoo.com/",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;
			}
		//get_contacts
		if ($this->checkResponse('export_post',$res))
			{
			$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
			$xpath=new DOMXPath($doc);$query="//input[@id='crumb2']";$data=$xpath->query($query);
			foreach($data as $val) 
				$post_elements=array("VPC"=>"import_export",
									$val->getAttribute('name')=>$val->getAttribute('value'),
									"submit[action_export_yahoo]"=>"Export+Now"
									);
			$res=$this->post("http://address.mail.yahoo.com/index.php",$post_elements,true);
			$this->updateDebugBuffer('export_post',"{$url_contacts}",'GET');
			}
		else 
			{
			$this->updateDebugBuffer('export_post',"{$url_contacts}",'POST',false);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;			
			}
		//get contacts
		if ($this->checkResponse("contacts_file",$res))
			{
			$temp=explode("\n",utf8_encode($res));
			unset($temp[0]);
			$contacts=array();
			foreach ($temp as $temp_contact)
				{
				$ok_id_mess=true;
				$contact_array=explode(',',str_replace('"','',$temp_contact));
				$name=(!empty($contact_array[0])?$contact_array[0]:'').(!empty($contact_array[1])?' '.$contact_array[1]:'').(!empty($contact_array[2])?' '.$contact_array[2]:'');
				if (!empty($contact_array[4]))
					{$contacts[$contact_array[4]]=(empty($name)?$contact_array[4]:$name);$ok_id_mes=false;}
				if (!empty($contact_array[16]))
					{$contacts[$contact_array[16]]=(empty($name)?$contact_array[16]:$name);$ok_id_mes=false;}
				if (!empty($contact_array[17]))
					{$contacts[$contact_array[17]]=(empty($name)?$contact_array[17]:$name);$ok_id_mes=false;}
				if ($ok_id_mess) if (!empty($contact_array[7])) $contacts["{$contact_array[7]}@yahoo.com"]=(empty($name)?$contact_array[7]:$name);
				}
			$this->updateDebugBuffer('contacts_file',"http://address.mail.yahoo.com/index.php",'POST',true,$post_elements);
			}
		else 
			{
			$this->updateDebugBuffer('contacts_file',"http://address.mail.yahoo.com/index.php",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;	
			}
		if ($this->settings['filter_emails'])
			$contacts=$this->filterEmails($contacts);
		return $contacts;
		}
		
	public function logout()
		{
		if (!$this->login_ok) return false;
		$res=$this->get("http://login.yahoo.com/config/login?logout=1&.done=http://address.yahoo.com&.src=ab&.intl=us",true,true);		
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}

	}
?>