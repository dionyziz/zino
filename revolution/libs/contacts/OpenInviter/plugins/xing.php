<?php
$_pluginInfo=array(
	'name'=>'Xing',
	'version'=>'1.0.3',
	'description'=>"Get the contacts from a Xing account",
	'base_version'=>'1.6.5',
	'type'=>'social',
	'check_url'=>'https://mobile.xing.com/'
	);
/**
 * Xing Plugin
 * 
 * Import user's contacts from Xing and send 
 * messages using the internal messaging system
 * 
 * @author OpenInviter
 * @version 1.0.0
 */
class xing extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='email';
	public $internalError=false;
	public $allowed_domains=false;
	
	public $debug_array=array(
				'initial_get'=>'dest',
				'post_login'=>'white',
				'get_friends'=>'normal_link',
				'get_url_send_message'=>'light_grey_bg',
				'url_send_message'=>'private_message.send',
				'send_message'=>'light_grey_bg'
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
	public function login($user,$pass)
		{
		$this->resetDebugger();
		$this->service='xing';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
		$res=$this->get("https://mobile.xing.com/");
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"https://mobile.xing.com/",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"https://mobile.xing.com/",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$form_action="https://mobile.xing.com/".$this->getElementString($res,'form action="','"');
		$post_elements=array('op'=>'login',
							 'dest'=>'/app/user?op=home',
							 'login_user_name'=>$user,
							 'login_password'=>$pass,
							 'sv-remove-name'=>'Log in'					
							);
		$res=$this->post($form_action,$post_elements,true);
		if ($this->checkResponse("post_login",$res))
			$this->updateDebugBuffer('post_login',$form_action,'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('post_login',$form_action,'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
			
		$url_adressbook_array=$this->getElementDOM($res,"//a[@class='white']",'href');
		$url_adressbook='https://mobile.xing.com'.$url_adressbook_array[3];		
		$this->login_ok=$url_adressbook;
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
		else $url=$this->login_ok;
		$res=$this->get($url,true);
		$contacts=array();
		if ($this->checkResponse("get_friends",$res))
			$this->updateDebugBuffer('get_friends',$url,'GET');
		else
			{
			$this->updateDebugBuffer('get_friends',$url,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$contacts_href=$this->getElementDOM($res,"//a[@class='normal_link']",'href');
		$contacts_name=$this->getElementDOM($res,"//a[@class='normal_link']");
		foreach ($contacts_name as $key=>$value)
			if (!empty($contacts_href[$key])) $contacts[$contacts_href[$key]]=!empty($value)?$value:false;	
		return $contacts;
		}

	/**
	 * Send message to contacts
	 * 
	 * Sends a message to the contacts using
	 * the service's inernal messaging system
	 * 
	 * @param string $cookie_file The location of the cookies file for the current session
	 * @param string $message The message being sent to your contacts
	 * @param array $contacts An array of the contacts that will receive the message
	 * @return mixed FALSE on failure.
	 */
	public function sendMessage($session_id,$message,$contacts)
		{
		foreach($contacts as $href=>$name)
			{
			$url_friend="https://mobile.xing.com{$href}";
			$res=$this->get($url_friend,true);
			if ($this->checkResponse("get_url_send_message",$res))
				$this->updateDebugBuffer('get_url_send_message',$url_friend,'GET');
			else
				{
				$this->updateDebugBuffer('get_url_send_message',$url_friend,'GET',false);
				$this->debugRequest();
				$this->stopPlugin();
				return false;
				}		
				$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
				$xpath=new DOMXPath($doc);$query="//div[@class='light_grey_bg']";$data=$xpath->query($query);
				foreach($data as $node) $url_send_message='https://mobile.xing.com/'.$node->nextSibling->getAttribute('href');
				$res=$this->get($url_send_message,true);
				if (strpos($res,'Only XING Premium')===false)
					{
					if ($this->checkResponse("url_send_message",$res))
						$this->updateDebugBuffer('url_send_message',$url_send_message,'GET');
					else
						{
						$this->updateDebugBuffer('url_send_message',$url_send_message,'GET',false);
						$this->debugRequest();
						$this->stopPlugin();
						return false;
						}
					$form_action="https://mobile.xing.com/".$this->getElementString($res,'form action="/','"');
					$post_elements=$this->getHiddenElements($res);$post_elements['subject']=$message['subject'];
					$post_elements['body']=$message['body'];$post_elements['sv-set-op-to-private_message.send']='Send';
					$res=$this->post($form_action,$post_elements,true);
					if ($this->checkResponse("post_login",$res))
						$this->updateDebugBuffer('send_message',$form_action,'POST',true,$post_elements);
					else
						{
						$this->updateDebugBuffer('send_message',$form_action,'POST',false,$post_elements);
						$this->debugRequest();
						$this->stopPlugin();
						return false;
						}
					}
			else return true;
			}	
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
		$res=$this->get("https://www.xing.com/app/user?op=logout",true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	}	

?>