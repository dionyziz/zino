<?php
$_pluginName='GMX.net';
$_pluginVersion='1.0.0';
$_pluginDescription="Get the contacts from GMX.net";
$_requiredBaseVersion="1.5.0";
$_pluginType='email';
class gmx_net extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	
	public $debug_array=array(
				'initial_get'=>'uinguserid',
				'login'=>'Adressbuch',
				'export_file'=>'b_export',
				'contacts_file'=>'","'
				);
	
	public function login($user,$pass)
		{
		//$this->resetDebugger();
		$this->service='gmx_net';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->curl=$this->init();

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
		$post_elements=$this->getHiddenElements($res,'','','','');$post_elements['dataformat']='o2002';$post_elements['language']='english';$post_elements['b_export']='Export starten';
		$res=$this->post($form_action,$post_elements,true);
		
		if ($this->checkResponse("contacts_file",$res))
			$this->updateDebugBuffer('contacts_file',$form_action,'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('contacts_file',$form_action,'POST',false,$post_elements);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$temp=explode(PHP_EOL,$res);
		unset($temp[0]);	
		$contacts=array();
		foreach ($temp as $temp_contact)
			{
			$contact_array=explode(',',str_replace(';',',',str_replace('"','',$temp_contact)));
			$name=(!empty($contact_array[1])?$contact_array[1]:'').(!empty($contact_array[2])?' '.$contact_array[2]:'').(!empty($contact_array[3])?' '.$contact_array[3]:'');
			if (!empty($contact_array[29]))
				$contacts[$contact_array[29]]=(empty($name)?$contact_array[29]:$name);
			}
		if ($this->settings['filter_emails'])
			$contacts=$this->filterEmails($contacts);
		return $contacts;	
		}
		
	public function logout()
		{
		if (!$this->login_ok)
			{
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		else $url=$this->login_ok;
		$res=$this->get($url,true);
		$logout_url="https://service.gmx.net/de/cgi/nph-logout?CUSTOMERNO=".$this->getElementString($res,"https://service.gmx.net/de/cgi/nph-logout?CUSTOMERNO=",'"');
		$res=$this->get($logout_url,true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	
	}	

?>