<?php
$_pluginName='Mail.com';
$_pluginVersion='1.0.5';
$_pluginDescription="Get the contacts from an mail.com";
$_requiredBaseVersion="1.5.0";
$_pluginType='email';
class mail_com extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	
	public $debug_array=array(
				'initial_get'=>'name="mailcom"',
				'login_post'=>'mailcomframe',
				'inbox'=>'outblaze',
				'export_page'=>'addrURL',
				'post_contacts'=>'csv',
				'file_contacts'=>'Title'
				);
	
	public function login($user,$pass)
		{
		$this->resetDebugger();
		$this->service='mail_com';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->curl=$this->init();
		
		//go to mail.com
		$res=$this->get("http://www.mail.com/",true);
		
		if ($this->checkResponse('initial_get',$res))
			$this->updateDebugBuffer('initial_get',"http://www.mail.com/",'GET');
		else 
			{
			$this->updateDebugBuffer('initial_get',"http://www.mail.com/",'GET',false);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		//get form action and post elements and post to post action 
		$form_action=$this->getElementString($res,'name="mailcom"  action="','"');
		$post_elements=array("login"=>"{$user}","password"=>"{$pass}","redirlogin"=>1,"siteselected"=>"normal");
		$res=$this->post($form_action,$post_elements,true);
		
		if ($this->checkResponse('login_post',$res))	
			$this->updateDebugBuffer('login_post',"{$form_action}",'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login_post',"{$form_action}",'POST',false,$post_elements);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}		
		//get the redirect
		$url_redirect=$this->getElementDOM($res,"//frame[@name='mailcomframe']",'attribute','src');
		$res=$this->get($url_redirect[0],true);
		
		$this->login_ok=$url_redirect[0];
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
		
		if ($this->checkResponse('inbox',$res))
			$this->updateDebugBuffer('login_post',"{$url}",'GET');
		else
			{
			$this->updateDebugBuffer('login_post',"{$url}",'GET',false);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}	
		$url_contacts=$this->getElementDOM($res,"//a[@id='addrURL']",'attribute','href');
		$res=$this->get($url_contacts[0],true);
		
		if ($this->checkResponse("export_page",$res))
			{
			$url_export="";
			$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
			$xpath=new DOMXPath($doc);$query="//a[@href]";$data=$xpath->query($query);
			foreach($data as $val) 
			if (strstr($val->nodeValue,"Import/Export")) $url_export=$val->getAttribute('href')."&gab=1";
			$this->updateDebugBuffer('post_contacts',"{$url_contacts[0]}",'GET');
			}
		else
			{
			$this->updateDebugBuffer('post_contacts',"{$url_contacts[0]}",'GET',false);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		//go to file contacts
		$post_elements=array("showexport"=>"showexport","action"=>"export","format"=>"csv");
		$res=$this->post($url_export,$post_elements,true);
		
		if ($this->checkResponse('file_contacts',$res))
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
			$this->updateDebugBuffer('login_post',"{$url_export}",'POST',true,$post_elements);
			}
		else
			{
			$this->updateDebugBuffer('login_post',"{$url_export}",'POST',false,$post_elements);	
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
		$res=$this->get("http://www.mail.com/logout.aspx");
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	
	}	

?>