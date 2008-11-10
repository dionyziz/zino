<?php
$_pluginName='Lycos';
$_pluginVersion='1.0.5';
$_pluginDescription="Get the contacts from Rambler.ru";
$_requiredBaseVersion="1.5.0";
$_pluginType='email';
class lycos extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	
	public $debug_array=array(
				'initial_get'=>'m_U',
				'login'=>'Compose',
				'export_url'=>'csv',
				'file_contacts'=>'First Name'
				);
	
	public function login($user,$pass)
		{
		//$this->resetDebugger();
		$this->service='lycos';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->curl=$this->init();
		
		//go to mail.ru
		$res=$this->get("http://lycos.com/");
		
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"http://lycos.com/",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://lycos.com/",'GET',false);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$get_elements=$this->getHiddenElements($res,"m_U",$user,"m_P",$pass);
		$url_login="http://registration.lycos.com/login.php?".http_build_query($get_elements);
		$res=$this->get($url_login,true);		
		
		//go to mail page
		if ($this->checkResponse("login",$res))
			$this->updateDebugBuffer('login',"http://registration.lycos.com/login.php?",'GET',true,$get_elements);
		else
			{
			$this->updateDebugBuffer('login',"http://registration.lycos.com/login.php?",'GET',false,$get_elements);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$res=$this->get("http://mail.lycos.com/?utm_source=Home%2BPage&amp;utm_medium=Menu&amp;utm_campaign=mail",false,true);
		
		
		
		//go to export url		
		$url_export="http://mail.lycos.com/lycos/addrbook/ExportAddr.lycos?ptype=act&fileType=OUTLOOK";
		
		$this->login_ok=$url_export;
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
		else $url=$this->login_ok;
		$res=$this->get($url,true);
		
		if ($this->checkResponse("file_contacts",$res))
			{
			$temp=explode(PHP_EOL,$res);
			unset($temp[0]);	
			$contacts=array();
			foreach ($temp as $temp_contact)
				{
				$contact_array=explode(',',str_replace(';',',',str_replace('"','',$temp_contact)));
				$name=(!empty($contact_array[1])?$contact_array[1]:'').(!empty($contact_array[2])?' '.$contact_array[2]:'').(!empty($contact_array[3])?' '.$contact_array[3]:'');
				if (!empty($contact_array[4]))
					$contacts[$contact_array[4]]=(empty($name)?$contact_array[4]:$name);
				if (!empty($contact_array[12]))
					$contacts[$contact_array[12]]=(empty($name)?$contact_array[12]:$name);
				if (!empty($contact_array[13]))
					$contacts[$contact_array[13]]=(empty($name)?$contact_array[13]:$name);
				}
			$this->updateDebugBuffer('file_contacts',"{$url}",'GET');
			}
		else
			{
			$this->updateDebugBuffer('file_contacts',"{$url}",'GET',false);	
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
		$res=$this->get("https://registration.lycos.com/logout.php",true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	
	}	

?>