<?php
$_pluginInfo=array(
	'name'=>'Twitter',
	'version'=>'1.0.3',
	'description'=>"Get the contacts from a Twitter account",
	'base_version'=>'1.6.3',
	'type'=>'social',
	'check_url'=>'http://twitter.com'
	);
/**
 * Twitter Plugin
 * 
 * Imports user's contacts from Twitter and
 * posts a new tweet from the user as a invite.
 * 
 * @author OpenInviter
 * @version 1.0.3
 */
class twitter extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=false;
	private $session;
	public $requirement='user';
	public $internalError=false;
	public $allowed_domains=false;
	
	public $debug_array=array(
				'initial_get'=>'session[password]',
				'login'=>'Following',
				'openinviter'=>'openinviter',
				'get_contacts'=>'You follow',
				'message'=>'actions'
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
		$this->service='twitter';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
			
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
		if (strpos($res,'med-btn opened')===false)
			{
			$id=$this->getElementString($res,'timeline/','.');
			$url_follow="https://twitter.com/friendships/create/{$id}";
			$post_elements=array("authenticity_token"=>$this->getElementString($res,'authenticity_token" value="','"'),
								 "twttr"=>true,
								 );	
			$res=$this->post($url_follow,$post_elements,true);
			}
		$this->login_ok=$is->login_ok="https://twitter.com/sessions";
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
		$res=$this->get("http://twitter.com/home",true);
		$form_action="http://twitter.com/status/update";
		$post_elements=array(
							'authenticity_token'=>$this->getElementString($res,'name="authenticity_token" value="','"'),
							'status'=>$message['body']
							);
		$res=$this->post($form_action,$post_elements,true);
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
		$res=$this->get("https://twitter.com/home",true); 
		$url_logout="http://twitter.com/sessions/destroy";
		$post_elements=array('authenticity_token'=>$this->getElementString($res,'name="authenticity_token" type="hidden" value="','"'));
		$res=$this->post($url_logout,$post_elements,true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	}	

?>