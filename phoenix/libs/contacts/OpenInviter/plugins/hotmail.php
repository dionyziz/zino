<?php
/*This plugin import Hotmail/Live contacts
 *You can send normal email   
 */
$_pluginName='Live / Hotmail';
$_pluginVersion='1.3.9';
$_pluginDescription="Get the contacts from a Windows Live! or a Hotmail account";
$_requiredBaseVersion="1.5.0";
$_pluginType='email';
class hotmail extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	
	public $debug_array=array(
				'initial_get'=>'srf_uPost=',
				'post_login'=>'function OnBack()',
				'url_print'=>'ContactsPrintPane',
				'get_contacts'=>'Title'
				);
	
	function login($user,$pass)
		{
		$this->resetDebugger();
		$this->service='hotmail';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->init();
		//go to mail.live.com		
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
		//get the url login and post varibles and send post to url login
		$res=$this->post($post_action,$post_elements,true);
		
		if ($this->checkResponse('post_login',$res))
			$this->updateDebugBuffer('post_login',"{$post_action}",'POST',true,$post_elements);
		else 
			{
			$this->updateDebugBuffer('post_login',"{$post_action}",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}
		//go to mail.live.com
		
		$res=$this->get("http://mail.live.com/",true,true,false);
		
		//get the redirect url
		$url_redirect=$this->getElementString($res,'Location: ','/TodayLight');
		$this->login_ok=$this->login_ok=$url_redirect;
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
			$base_url=$this->login_ok;
		$contacts=array();
		$url_contacts=$base_url."/GetContacts.aspx?n=";
		//get the contacts URL		
		
		$res=$this->get($url_contacts,true);
		
		//hotmail ask to confirm the new look
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
		
		if ((empty($res)) OR ((strpos($res,'mt')!==false))) 
			{
			//for new type of users go to inbox 
			$res=$this->get("http://mail.live.com/default.aspx?wa=wsignin1.0",true);
			
			//go to print_url for new type of users (file contacts doesn't work->javascript random hidden element insert)
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
				$nodes_name=$node->childNodes->item( 2 );$name=trim(preg_replace('/[^(\x20-\x7F)]*/','',(string)$nodes_name->nodeValue));
				$nodes_email=$node->childNodes->item( 4 );$brut_email=(string)$nodes_email->nodeValue;
				$array_email=explode(":",$brut_email);
				if (!empty($array_email[count($array_email)-1]))
					if (strpos($array_email[count($array_email)-1],'@')) $contacts[trim(preg_replace('/[^(\x20-\x7F)]*/','',$array_email[count($array_email)-1]))]=$name;
				}  				
			}		
		elseif ((!empty($res) AND (strpos($res,'mt')===false)))
				{
				if ($this->checkResponse("get_contacts",$res))
					{
					//get the contacts from file
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
		if ($this->settings['filter_emails'])
			$contacts=$this->filterEmails($contacts);
		return $contacts;
		}
	
	public function logout()
		{
		if (!$this->login_ok)
			return false;
		else
			$url=$this->login_ok;
		//go to url mail 
		$res=htmlspecialchars($this->get($url,true));
		$url_logout=$url."logout.aspx";		
		//go to logout url
		$res=$this->get($url_logout,true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}
		
	}
?>