<?php
/*Import Friends from Facebook
 * You can write message to your Friends Wall
 */
$_pluginName='Facebook';
$_pluginVersion='1.0.4';
$_pluginDescription="Get the contacts from Facebook";
$_requiredBaseVersion="1.5.0";
$_pluginType='social';
class facebook extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='email';
	public $internalError=false;
	public $allowed_domains=false;
	
	public $debug_array=array(
				'initial_get'=>'pass',
				'login'=>'accesskey',
				'friends'=>'profile.php?id=',
				'message'=>'body'
				);
	
	public function login($user,$pass)
		{
		$this->resetDebugger();
		$this->service='facebook';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->curl=$this->init();
		
		//go to facebook mobile
		$res=$this->get("http://m.facebook.com/",true);
		if ($this->checkResponse("initial_get",$res))
			$this->updateDebugBuffer('initial_get',"http://m.facebook.com/",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://m.facebook.com/",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$form_action=$this->getElementString($res,'form action="','"');
		$post_elements=array('email'=>$user,
							 'pass'=>$pass,
							 'login'=>'Log In',
							);
		//get the post variables and go to url login
		$res=$this->post($form_action,$post_elements,true);
		$friends_url=html_entity_decode("http://m.facebook.com/friends".$this->getElementString($res,'a href="/friends','"'));
		
		$res=$this->get($friends_url,true);
		
		if ($this->checkResponse("login",$res))
			$this->updateDebugBuffer('login',$form_action,'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login',$form_action,'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		
		$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
		$xpath=new DOMXPath($doc);$query="//a[@accesskey='8']";$data=$xpath->query($query);
		foreach ($data as $node) $all_friends_url=html_entity_decode("http://m.facebook.com".$node->getAttribute('href'));

		$this->login_ok=$all_friends_url;
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
		
		//go to All friends url
		$res=$this->get($url,true);			
		if ($this->checkResponse("friends",$res))
			$this->updateDebugBuffer('friends',$url,'GET');
		else
			{
			$this->updateDebugBuffer('friends',$url,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		//get  Friends
		$curent_friends=0;$contacts=array();$nr_of_friends_string=$this->getElementString($res,'"summary"><small>','.');$nr_of_friends_array=explode(" ",$nr_of_friends_string);if (!empty($nr_of_friends_array[4])) $nr_friends=$nr_of_friends_array[4];else $nr_friends=1;
		while($curent_friends<$nr_friends)
			{
			$url_next=false;
			$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
			$xpath=new DOMXPath($doc);$query="//a[@href]";$data=$xpath->query($query);$name="";$href="";
			foreach ($data as $node)
				{
				if ((strpos($node->getAttribute('href'),'profile.php?id=')) and ($node->nodeValue!='Profile')) 
					{$name=$node->nodeValue;$href=$node->getAttribute('href');$contacts[$href]=$name;$curent_friends++;}
				if (strpos($node->getAttribute('href'),'friends.php?a&f')!==false) 
					$url_next="http://m.facebook.com/".$node->getAttribute('href');
				}
			if ($url_next) $res=$this->get($url_next,true);
			}		
		return $contacts;
		
		}

	public function sendMessage($cookie_file,$message,$contacts)
		{
		// get the cookie file
		$this->curl=$this->init($cookie_file);
		foreach ($contacts as $link=>$name)
			{
			$compose_url=html_entity_decode("http://m.facebook.com{$link}");
			//go to friend url
		 	$res=$this->get($compose_url,true);
			
			if ($this->checkResponse("message",$res))
				$this->updateDebugBuffer('message',$compose_url,'GET');
			else
				{
				$this->updateDebugBuffer('message',$compose_url,'GET',false);
				$this->debugRequest();
				$this->stopPlugin();
				return false;
				}
			
			$form_action="http://m.facebook.com/".$this->getElementString($res,'form action="','"');
			$post_elements=array('post_form_id'=>$this->getElementString($res,'post_form_id" value="','"'),
								 'wall_post'=>"{$message['subject']}\n{$message['body']}",
								 'post'=>'Post'
								);
			//get the post elements and send the Wall message to form action 
			$res=$this->post($form_action,$post_elements,true);	
			
			}
		}
		
	public function logout()
		{		
		if (!$this->login_ok)
			{
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		else $url=$this->login_ok;
		//get to url friends and get the logout url
		$res=$this->get($url,true);
		$url_logout="http://www.facebook.com/logout.php?h=".$this->getElementString($res,'http://www.facebook.com/logout.php?h=','"');
		//go to url logout
		$res=$this->get($url_logout,true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	}	

?>