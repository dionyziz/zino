<?php
/*Import Friends from Myspace
 * You can send private message using Myspace system to your Friends
 */
$_pluginName='Myspace';
$_pluginVersion='1.0.1';
$_pluginDescription="Get the contacts from Myspace";
$_requiredBaseVersion="1.5.0";
$_pluginType='social';
class myspace extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	
	public $debug_array=array(
				'initial_get'=>'__VIEWSTATE',
				'login'=>'Compose',
				'get_url_friends'=>'.star',
				'url_friends'=>'friendHelperBox'
				);
	
	public function login($user,$pass)
		{
		$this->resetDebugger();
		$this->service='myspace';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->curl=$this->init();
		
		//go to myspace
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
		//go to login
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
		//get url_friends
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
		
	public function getMyContacts()
		{
		if (!$this->login_ok)
			{
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		else $url=$this->login_ok;
		//go to url firends
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
		//get friends
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

	public function sendMessage($cookie_file,$message,$contacts)
		{
		//get the cookie
		$this->curl=$this->init($cookie_file);
		
		//go to profile friend
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
			//get the post varibles and send post to url_messaging
			$res=$this->post($url_messaging,$post_elements,true);
			}	
		}
		
	public function logout()
		{
		//go to logout url
		$res=$this->get("http://www.myspace.com/index.cfm?fuseaction=signout",true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	}	

?>