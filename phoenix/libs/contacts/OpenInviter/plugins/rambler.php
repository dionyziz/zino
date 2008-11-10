<?php
/*
 * Created on Sep 25, 2008
 *
 * Owner: Doru
 * 
 */
$_pluginName='Rambler.ru';
$_pluginVersion='1.0.5';
$_pluginDescription="Get the contacts from Rambler.ru";
$_requiredBaseVersion="1.5.0";
$_pluginType='email';
class rambler extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	
	public $debug_array=array(
				'initial_get'=>'login',
				'login_post'=>'ramac_add_handler',
				'pop_up_contacts'=>'evt_cancel(event)'
				);
	
	public function login($user,$pass)
		{
		$this->resetDebugger();
		$this->service='rambler';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->curl=$this->init();
		
		//go to rambler.ru
		$res=$this->get("http://www.rambler.ru/",true);
		
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"http://www.rambler.ru/",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://www.rambler.ru/",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$post_elements=$this->getHiddenElements($res,"login",$user,"passw",$pass);
		//first key template val 
		unset($post_elements[0]);
		$res=$this->post("http://id.rambler.ru/script/auth.cgi",$post_elements,true);
		
		//get the contact url
		if ($this->checkResponse("login_post",$res))
			$this->updateDebugBuffer('login_post',"http://id.rambler.ru/script/auth.cgi",'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login_post',"http://id.rambler.ru/script/auth.cgi",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		
		$url_contact_array=$this->getElementDOM($res,"//a[@id='addressbook-link']",'attribute','href');
		$value=substr($url_contact_array[0],strpos($url_contact_array[0],"r=")+2,strlen($url_contact_array[0])-strpos($url_contact_array[0],"r=")-2);
		$url_contact="http://mail.rambler.ru/mail/contacts.cgi?mode=popup;{$value}";
		$this->login_ok=$url_contact;
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
		if ($this->checkResponse("pop_up_contacts",$res))
			$this->updateDebugBuffer('pop_up_contacts',$url,'GET');
		else
			{
			$this->updateDebugBuffer('pop_up_contacts',$url,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}			
		$contacts=array();
		$array_result=explode(PHP_EOL,$res);
		foreach($array_result as $key=>$val)
			if (strpos($val,'evt_cancel(event);">')!==false)
				if (!empty($array_result[$key+1]))
					$contacts[$this->getElementString($val,'evt_cancel(event);">',"<")]=strip_tags($array_result[$key+1]);
						
		if ($this->settings['filter_emails'])
			$contacts=$this->filterEmails($contacts);
		return $contacts;
						
		}
		
	public function logout()
		{
		$url=$this->login_ok;
		$url_logout=str_replace("http://","http://id.",str_replace("contacts.cgi?mode=popup;","auth.cgi?back=;mode=logout;r=",$url));
		$res=$this->get($url_logout,true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}
	
	}	
?>