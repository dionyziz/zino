<?php
/*Import Friends from Friendster.com
 * You can send private message using Friendster system to your Friends
 */
$_pluginName='Friendster';
$_pluginVersion='1.0.0';
$_pluginDescription="Get the contacts from Frienster";
$_requiredBaseVersion="1.5.0";
$_pluginType='social';
class friendster extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	
	public $debug_array=array(
				'initial_get'=>'tzoffset',
				'login_post'=>'dict:snWhosViewedMe',
				'contacts'=>'thumbnaildelete',
				'message_compose'=>'msg_type',
				'message_send'=>'noliststyle noindent'
				);
	
	public function login($user,$pass)
		{
		$this->resetDebugger();
		$this->service='friendster';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->curl=$this->init();
		
		//go to frindster
		$res=$this->get("http://www.friendster.com/");
		
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"http://www.friendster.com/",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://www.friendster.com/",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		
		//make the login
		$form_action="http://www.friendster.com/login.php";
		$post_elements=array('tzoffset'=>1,
							 'next'=>'/',
							 '_submitted'=>1,
							 'email'=>$user,
							 'password'=>$pass						
							);
		$res=$this->post($form_action,$post_elements,true);
		
		if ($this->checkResponse("login_post",$res))
			$this->updateDebugBuffer('login_post',"{$form_action}",'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login_post',"{$form_action}",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		
		$url_friends="http://www.friendster.com/friends.php";	
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
		
		//go to url friends
		$res=$this->get($url,true);
		
		if ($this->checkResponse('contacts',$res))
			$this->updateDebugBuffer('contacts',$url,'GET');
		else
			{
			$this->updateDebugBuffer('contacts',$url,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$contacts=array();
		$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
			$xpath=new DOMXPath($doc);$query="//span[@class='strong']";$data=$xpath->query($query);
			foreach ($data as $node)
				$contacts[str_replace("http://profiles.friendster.com/","",(string)$node->firstChild->getAttribute('href'))]=(string)$node->firstChild->nodeValue;					
		return $contacts;
		}

	public function sendMessage($cookie_file,$message,$contacts)
		{
		//get the cookie
		$this->curl=$this->init($cookie_file);
		
		foreach ($contacts as $id=>$name)
			{
			//go to message url friend
			$res=$this->get("http://www.friendster.com/sendmessage.php?uid={$id}",true);
			
			if ($this->checkResponse('message_compose',$res))
				$this->updateDebugBuffer('message_compose',"http://www.friendster.com/sendmessage.php?uid={$id}",'GET');
			else
				{
				$this->updateDebugBuffer('message_compose',"http://www.friendster.com/sendmessage.php?uid={$id}",'GET',false);
				$this->debugRequest();
				$this->stopPlugin();
				return false;
				}
			
			//send the message
			$form_action="http://www.friendster.com/sendmessage.php";
			$post_elements=$this->getHiddenElements($res,'message',$message['body'],'subject',$message['subject']);
			$res=$this->post($form_action,$post_elements,true);
			
			if ($this->checkResponse('message_send',$res))
				$this->updateDebugBuffer('message_send',"{$form_action}",'POST',true,$post_elements);
			else
				{
				$this->updateDebugBuffer('message_send',"{$form_action}",'POST',false,$post_elements);
				$this->debugRequest();
				$this->stopPlugin();
				return false;
				}
			
			}		
		}
		
	public function logout()
		{
		//go to logout url
		$res=$this->get("http://www.friendster.com/logout.php",true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	}
	
?>