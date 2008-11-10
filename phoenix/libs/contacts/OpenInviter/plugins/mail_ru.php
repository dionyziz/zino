<?php
$_pluginName='Mail.ru';
$_pluginVersion='1.0.5';
$_pluginDescription="Get the contacts from mail.ru";
$_requiredBaseVersion="1.5.0";
$_pluginType='email';
class mail_ru extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	
	public $debug_array=array(
				'initial_get'=>'login',
				'login_post'=>'mra_confirm',
				'file_contacts'=>'"'
				);
	
	public function login($user,$pass)
		{
		$this->resetDebugger();
		$this->service='mail_ru';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->curl=$this->init();
		
		//go to mail.ru
		$res=$this->get("http://www.mail.ru/",true);
		
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"http://www.mail.ru/",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://www.mail.ru/",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		//get domaine from user mail
		$array_user=explode("@",$user);$domain=strtolower($array_user[1]);
		$hidden_element=$this->getElementDOM($res,"//input[@name='Mpopl']","attribute","value");
		$post_elements=array('Domain'=>$domain,'Login'=>$user,'Password'=>$pass,'Mpopl'=>$hidden_element[0]);
		$res=$this->post("http://win.mail.ru/cgi-bin/auth",$post_elements,true);
		
		if ($this->checkResponse("login_post",$res))
			$this->updateDebugBuffer('login_post',"http://win.mail.ru/cgi-bin/auth",'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login_post',"http://win.mail.ru/cgi-bin/auth",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$url_export="http://win.mail.ru/cgi-bin/abexport/addressbook.csv";
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
			
		$post_elements=array("confirm"=>"1","abtype"=>"1");
		$res=$this->post($url,$post_elements,true);
		
		if ($this->checkResponse("file_contacts",$res))
			{
			$temp=explode(PHP_EOL,$res);
			unset($temp[0]);	
			$contacts=array();
			foreach ($temp as $temp_contact)
				{
				$contact_array=explode(',',str_replace(';',',',str_replace('"','',$temp_contact)));
				$name=(!empty($contact_array[0])?$contact_array[0]:'').(!empty($contact_array[1])?' '.$contact_array[1]:'').(!empty($contact_array[2])?' '.$contact_array[2]:'');
				if (!empty($contact_array[8]))
					$contacts[$contact_array[8]]=(empty($name)?$contact_array[8]:$name);
				if (!empty($contact_array[9]))
					$contacts[$contact_array[9]]=(empty($name)?$contact_array[9]:$name);
				}
			$this->updateDebugBuffer('file_contacts',"{$url}",'POST',true,$post_elements);
			}
		else
			{
			$this->updateDebugBuffer('file_contacts',"{$url}",'POST',false,$post_elements);	
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
		$res=$this->get("http://win.mail.ru/cgi-bin/logout",true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	
	}	

?>