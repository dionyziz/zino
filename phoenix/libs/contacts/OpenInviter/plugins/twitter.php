<?php
$_pluginName='Twitter';
$_pluginVersion='1.0.0';
$_pluginDescription="Get the contacts from Twitter";
$_requiredBaseVersion="1.5.0";
$_pluginType='social';
class twitter extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=false;
	private $session;
	
	public $debug_array=array(
				'initial_get'=>'session[password]',
				'login'=>'Following',
				'openinviter'=>'openinviter',
				'get_contacts'=>'You follow',
				'message'=>'actions'
				);
	
	public function login($user,$pass)
		{
		//$this->resetDebugger();
		$this->service='twitter';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->curl=$this->init();
		
		$res=$this->get("http://twitter.com/");
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"http://twitter.com/",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://twitter.com/",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$post_elements=array(
							'session[username_or_email]'=>$user,
							'session[password]'=>$pass,
							'remember_me'=>1
							);
		$res=$this->post("https://twitter.com/sessions",$post_elements,true);
		
		if ($this->checkResponse("login",$res))
			$this->updateDebugBuffer('login',"https://twitter.com/sessions",'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login',"http://twitter.com/",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
			
		$res=$this->get("https://twitter.com/openinviter",true);
		if ($this->checkResponse("openinviter",$res))
			$this->updateDebugBuffer('opeinviter',"https://twitter.com/openinviter",'GET');
		else
			{
			$this->updateDebugBuffer('openinviter',"https://twitter.com/openinviter",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;	
			}
		if (strpos($res,'followDetails')===false)
			{
			$elements=explode(',',str_replace("'","",str_replace('"follow(',"",$this->getElementString($res,'id="follow_button" onclick=',"')"))));
			$url_follow="https://twitter.com/friendships/create/{$elements[0]}?authenticity_token=".ltrim($elements[2]);
			$res=$this->post($url_follow,false,true,true);
			}
		$this->login_ok=$this->login_ok="https://twitter.com/sessions";
		return true;
		}
		
	public function getMyContacts()
		{
		//get contacts
		$page=0;$contacts=array();$message_list=array();
		do
			{
			$returned=0;$page++;$page_contacts="https://twitter.com/{$this->service_user}/friends?page={$page}";
			$res=$this->get($page_contacts,true);
			if ($this->checkResponse("get_contacts",$res))
				$this->updateDebugBuffer('get_contacts',$page_contacts,'GET');
			else
				{
				$this->updateDebugBuffer('get_contacts',$page_contacts,'GET',false);
				$this->debugRequest();
				$this->stopPlugin();
				return false;	
				}
			$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
			$xpath=new DOMXPath($doc);$query="//a[@class='url uid']";$data=$xpath->query($query);
			foreach ($data as $node)
				{
				$id=str_replace("actions","",(string)$node->parentNode->nextSibling->nextSibling->getAttribute('id'));
				if (strpos($res,"/direct_messages/create/{$id}")!==false)  $message_list[$id]=$id;
				$contacts[strip_tags($node->nodeValue)]=strip_tags($node->nodeValue);
				$returned++;
				}
			}
		while($returned>0);
		
		return $contacts;	
		}

//	public function sendDirectMessages($message_list,$message)
//		{
//			
//		$res=$this->get("http://twitter.com/home",true);
//		foreach ($message_list as $key=>$ids)
//			{
//			$form_action="http://twitter.com/direct_messages/create/{$ids}";
//			$post_elements=array(
//								'authenticity_token'=>$this->getElementString($res,'id="form_auth_token" value="','"'),
//								'text'=>$message,
//								);
//			$res=$this->post($form_action,$post_elements,true);
//			}	
//		}
//				
	public function sendMessage($cookie_file,$message,$contacts)
		{
		$userAgent="Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1";
		$this->curl=$this->init($cookie_file);
		$res=$this->get("http://twitter.com/home",true);
		$form_action="http://twitter.com/status/update";
		$post_elements=array(
							'authenticity_token'=>$this->getElementString($res,'name="authenticity_token" value="','"'),
							'status'=>$message['body']
							);
		$res=$this->post($form_action,$post_elements,true);
		}
		
	public function logout()
		{
		$res=$this->get($this->login_ok,true);
		$url_logout="http://twitter.com/sessions/destroy";
		$post_elements=array('authenticity_token'=>$this->getElementString($res,'id="form_auth_token" value="','"'));
		$res=$this->post($url_logout,$post_elements,true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	}	

?>