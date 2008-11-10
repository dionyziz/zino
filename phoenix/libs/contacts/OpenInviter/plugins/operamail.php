<?php
/*This plugin import Operamail contacts
 *You can send normal email   
 */
$_pluginName='OperaMail';
$_pluginVersion='1.0.0';
$_pluginDescription="Get the contacts from OperaMail";
$_requiredBaseVersion="1.5.0";
$_pluginType='email';
class operamail extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	
	public $debug_array=array(
				'initial_get'=>'login',
				'login_post'=>'main?.ob',
				'file_contacts'=>'"'
				);
	
	public function login($user,$pass)
		{
		$this->resetDebugger();
		$this->service='operamail';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->curl=$this->init();
		
		//go to operamail
		$res=$this->get("http://www.operamail.com/scripts/common/index.main?signin=1&lang=us",true);
		
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"http://www.operamail.com/scripts/common/index.main?signin=1&lang=us",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://www.operamail.com/scripts/common/index.main?signin=1&lang=us",'GET',false);	
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$form_action="http://www.operamail.com/scripts/common/proxy.main";
		$post_elements=$this->getHiddenElements($res,'login',$user,'password',$pass);
		//get the post varibles and send post to form action 
		$res=$this->post($form_action,$post_elements,true);
		
		if ($this->checkResponse("login_post",$res))
			$this->updateDebugBuffer('login_post',$form_action,'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login_post',$form_action,'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}

		$url_file_contacts="http://mymail.operamail.com/scripts/addr/external.cgi?.ob=&gab=1";
			
		$this->login_ok=$url_file_contacts;
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
		
		$form_action=$url;
		$post_elements=array('showexport'=>'showexport',
							 'action'=>'export',
							 'login'=>$this->service_user,
							 'format'=>'csv'
							 );
		//get the post varibles and send post to form action
		$res=$this->post($form_action,$post_elements,true);
		
		if ($this->checkResponse("file_contacts",$res))
			$this->updateDebugBuffer('file_contacts',$form_action,'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('file_contacts',$form_action,'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		//get the contacts
		$temp=explode(PHP_EOL,$res);
		unset($temp[0]);	
		$contacts=array();
		foreach ($temp as $temp_contact)
			{
			$contact_array=explode(',',str_replace(';',',',str_replace('"','',$temp_contact)));
			$name=(!empty($contact_array[1])?$contact_array[1]:'').(!empty($contact_array[2])?' '.$contact_array[2]:'').(!empty($contact_array[3])?' '.$contact_array[3]:'');
			if ((!empty($contact_array[4])) AND ($contact_array[4]!='undefined'))
				$contacts[$contact_array[4]]=(empty($name)?$contact_array[4]:$name);	
			}						
		if ($this->settings['filter_emails'])
			$contacts=$this->filterEmails($contacts);
		return $contacts;
						
		}
		
	public function logout()
		{
		//go to logout url
		$url_logout="http://mymail.operamail.com/scripts/mail/Outblaze.mail?logout=1&.noframe=1&a=1&";
		$res=$this->get($url_logout,true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}
	
	}	

?>