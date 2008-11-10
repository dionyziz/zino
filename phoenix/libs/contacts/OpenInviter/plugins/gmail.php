<?php
$_pluginName='GMail';
$_pluginVersion='1.3.6';
$_pluginDescription="Get the contacts from a GMail account";
$_requiredBaseVersion="1.5.0";
$_pluginType='email';
class gmail extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	
	public $debug_array=array(
	  'login_post'=>'Redirecting',
	  'basic_html'=>'?redir=%2Fmail%2F&amp;a=stsv&amp;at=',
	  'contacts_post'=>'Name,E-mail Address,Notes,E-mail 2'
	);
	
	public function login($user,$pass)
		{
		$this->resetDebugger();
		$this->service='gmail';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->init();
		
		//login
	
		$res=$this->get("http://mail.google.com/mail/x/?source=mobileproducts&amp;dc=gorganic",true);
				
		$post_elements=$this->getHiddenElements($res,'Email',$user,"Passwd",$pass);
	    $res=htmlentities($this->post("https://www.google.com/accounts/ServiceLoginAuth",$post_elements,true));

		if ($this->checkResponse("login_post",$res))
			$this->updateDebugBuffer('login_post',"https://www.google.com/accounts/ServiceLoginAuth",'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login_post',"https://www.google.com/accounts/ServiceLoginAuth",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$url_redirect=$this->getElementString($res,'#39;','#39;');
		$this->get($url_redirect,true);
		
		// go to basic html
		$res=$this->get("https://mail.google.com/mail/?ui=html&zy=e",true);
		//get contact url
		if ($this->checkResponse("basic_html",$res))
			$this->updateDebugBuffer('basic_html',"https://mail.google.com/mail/?ui=html&zy=e",'GET');			
		else	
			{
			$this->updateDebugBuffer('basic_html',"https://mail.google.com/mail/?ui=html&zy=e",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$base_url=$this->getElementDOM($res,"//base[@href]",'attribute','href');
		$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML(utf8_encode($res));libxml_use_internal_errors(false);
		$xpath=new DOMXPath($doc);$data=$xpath->query("//a[@href]");
		foreach($data as $node) if (strpos((string)$node->getAttribute('href'),'?v=cl')!==false) $url_contact=$base_url[0].$node->getAttribute('href')."&pnl=a";
		
		$this->login_ok=$this->login_ok=$url_contact;
		return true;
		}
		
	public function getMyContacts()
		{
		if ($this->login_ok===false)
			{
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		else $url=$this->login_ok;
		$res=$this->get("https://mail.google.com/mail/?ui=1&ik=&view=sec&zx=",true);
		$contacts=array();
		$post_elements=array("at"=>$this->getElementString($res,'value="','"'),
								  "ecf"=>"o"
								 );
		$res=$this->post("https://mail.google.com/mail/?ui=1&view=fec",$post_elements,true);
		if ($this->checkResponse("contacts_post",$res))
			{
			$lines=explode(PHP_EOL,$res);unset($lines[0]);
			foreach($lines as $line)
				{
				$line_divided=explode(",",$line);
				if (!empty($line_divided[0]))
					{
					if (!empty($line_divided[1])) $contacts[$line_divided[1]]=$line_divided[0];
					elseif (!empty($line_divided[4])) $contacts[$line_divided[4]]=$line_divided[0];
					elseif (!empty($line_divided[5])) $contacts[$line_divided[5]]=$line_divided[0];
					}
				else
					{
					if (!empty($line_divided[1])) $contacts[$line_divided[1]]=$line_divided[1];
					elseif (!empty($line_divided[4])) $contacts[$line_divided[4]]=$line_divided[4];
					elseif (!empty($line_divided[5])) $contacts[$line_divided[5]]=$line_divided[5];
					}		
				}
			$this->updateDebugBuffer('contacts_post',"https://mail.google.com/mail/?ui=1&view=fec",'POST',true,$post_elements);
			}
		else
			{
			$this->updateDebugBuffer('contacts_post',"https://mail.google.com/mail/?ui=1&view=fec",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
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
		$res=$this->get($url,true);
		$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
		$xpath=new DOMXPath($doc);$data=$xpath->query("//a[@href]");
		foreach($data as $node) if (strpos((string)$node->getAttribute('href'),'logout')!==false)$url_logout=$node->getAttribute('href');
		if (!empty($url_logout)) $res=$this->get($url_logout,true,true);
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}
	}
?>