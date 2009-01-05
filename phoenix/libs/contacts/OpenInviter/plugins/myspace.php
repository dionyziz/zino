<?php
$_pluginInfo=array(
	'name'=>'MySpace',
	'version'=>'1.0.5',
	'description'=>"Get the contacts from a MySpace account",
	'base_version'=>'1.6.3',
	'type'=>'social',
	'check_url'=>'http://www.myspace.com'
	);
/**
 * MySpace Plugin
 * 
 * Import user's contacts from MySpace and send 
 * messages using the internal messaging system
 * 
 * @author OpenInviter
 * @version 1.0.5
 */
class myspace extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='email';
	public $internalError=false;
	public $allowed_domains=false;
	
	public $debug_array=array(
				'initial_get'=>'__VIEWSTATE',
				'login'=>'Compose',
				'get_url_friends'=>'profile.interests',
				'url_friends'=>'friendHelperBox'
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
		$this->service='myspace';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
		$res=$this->get("http://www.myspace.com/");
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"http://www.myspace.com/",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://www.myspace.com/",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$form_action="http://secure.myspace.com/index.cfm?fuseaction=login.process";
		$post_elements=array('__VIEWSTATE'=>$this->getElementString($res,'id="__VIEWSTATE" value="','"'),
							 'NextPage'=>'',
							 'ctl00_ctl00_cpMain_cpMain_LoginBox_Email_Textbox'=>$user,
							 'ctl00_ctl00_cpMain_cpMain_LoginBox_Password_Textbox'=>$pass,
							 'dlb'=>'Log In',
							 'ctl00_ctl00_cpMain_cpMain_LoginBox_SingleSignOnHash'=>'',
							 'ctl00_ctl00_cpMain_cpMain_LoginBox_SingleSignOnRequestUri'=>'',
							 'ctl00_ctl00_cpMain_cpMain_LoginBox_nexturl'=>'',
							 'ctl00_ctl00_cpMain_cpMain_LoginBox_apikey'=>'',
							 'ctl00_ctl00_cpMain_cpMain_LoginBox_ContainerPage'=>''							
							);
		$res=$this->post($form_action,$post_elements,true);
		if ($this->checkResponse("get_url_friends",$res))
			$this->updateDebugBuffer('get_url_friends',$form_action,'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('get_url_friends',$form_action,'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$url_friends="http://friends.myspace.com/index.cfm?fuseaction=user.viewfriends&friendID=".$this->getElementString($res,'"UserId":',',');
		$this->login_ok=$url_friends;
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
		if ($this->checkResponse("url_friends",$res))
			$this->updateDebugBuffer('url_friends',$url,'GET');
		else
			{
			$this->updateDebugBuffer('url_friends',$url,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$has_next=true;$page=-1;$contacts=array();
		do
			{
			if (strpos($res,'nextPagingLink disabledPaging')!==false) $has_next=false;
			else	
				{
				$page++;
				$page_next=$this->getElementString($res,"var urlHelper = new FriendsCategories.UrlHelper('","'");
				$fid=$this->getElementString($res,"urlHelper.SetQueryValue('fid', '","'");
				$lid=$this->getElementString($res,"urlHelper.SetQueryValue('lid', '","'");
				$page_next.="&p={$page}&j=1&fid={$fid}&lid={$lid}";
				$res=$this->get($page_next,true);
				}
			$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
			$xpath=new DOMXPath($doc);$query="//div[@class='friendHelperBox']";$data=$xpath->query($query);
			foreach ($data as $node)
				$contacts[$node->getAttribute('friendid')]=(string)$node->nodeValue;
			}
		while($has_next);	
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
		$res=$this->get("http://friends.myspace.com/index.cfm?fuseaction=user.viewfriends&friendID=",true);
		$mytokenvar=$this->getElementString($res,"MyToken=","')");
		foreach($contacts as $id=>$name)
			{
			$url_messaging="http://messaging.myspace.com/index.cfm?fuseaction=mail.message&friendID={$id}&MyToken={$mytokenvar}";
			$res=$this->get($url_messaging,true);
			$post_elements=array('__LASTFOCUS'=>'',
								 '__EVENTTARGET'=>'ctl00$ctl00$ctl00$cpMain$cpMain$messagingMain$SendMessage$btnSend',
								 '__EVENTARGUMENT'=>'',
								 '__VIEWSTATE'=>$this->getElementString($res,'id="__VIEWSTATE" value="','"'),
								 '___msUniqueVal'=>$this->getElementString($res,'id="___msUniqueVal" value="','"'),
								 'ctl00$ctl00$ctl00$cpMain$cpMain$messagingMain$SendMessage$selectedRecipient'=>'',
								 'ctl00$ctl00$ctl00$cpMain$cpMain$messagingMain$SendMessage$selectedRecipientName'=>'',
								 'ctl00$ctl00$ctl00$cpMain$cpMain$messagingMain$SendMessage$subjectTextBox'=>$message['subject'],
								 'ctl00$ctl00$ctl00$cpMain$cpMain$messagingMain$SendMessage$ieHack'=>'',
								 'ctl00$ctl00$ctl00$cpMain$cpMain$messagingMain$SendMessage$bodyTextBox'=>$message['body'],
								 'ctl00$ctl00$ctl00$cpMain$cpMain$messagingMain$SendMessage$saveDraftGuid'=>'',
								 'ctl00$ctl00$ctl00$cpMain$cpMain$messagingMain$SendMessage$MessageInfoData'=>'',
								 'ctl00$ctl00$ctl00$cpMain$cpMain$messagingMain$SendMessage$FriendInfoData'=>$this->getElementString($res,'id="ctl00_ctl00_ctl00_cpMain_cpMain_messagingMain_SendMessage_FriendInfoData" value="','"'),
								);
			$res=$this->post($url_messaging,$post_elements,true);
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
		$res=$this->get("http://www.myspace.com/index.cfm?fuseaction=signout",true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	}	

?>