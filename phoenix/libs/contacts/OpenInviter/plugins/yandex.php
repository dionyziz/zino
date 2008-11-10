<?php
$_pluginName='Yandex';
$_pluginVersion='1.0.0';
$_pluginDescription="Get the contacts from yandex.ru";
$_requiredBaseVersion="1.5.0";
$_pluginType='email';
//imports email addresses from yandex.ru
//sends normal email
class yandex extends OpenInviter_base
{
	private $login_ok=false;
	public $showContacts=false;
	public $debug_array=array(
			  'main_redirect'=>'window.location.replace(&quot;',
			  'log_in'=>'http://passport.yandex.ru/passport?mode=logout'
	);
	public function login($user, $pass)
	{
		$this->resetDebugger();
		$this->service='yandex';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->curl=$this->init();
		//get initial cookie
		$res = $this->get("http://yandex.ru/",true);
		//go to login page
		$res = $this->get("http://mail.yandex.ru/",true);
		//this is the url from the 'action' atribute of the <form> tag from the login form.
		$postaction = "https://passport.yandex.ru/passport?mode=auth";
		//this is the array with post variables mandatory for the login operation
		$postelem = $this->getHiddenElements($res, "login", $user, "passwd", $pass);
		//try to login
		$res = $this->post($postaction, $postelem, true);
		$res =  htmlentities($res);
		//test for login succes
		if ($this->checkResponse("main_redirect",$res))
			$this->updateDebugBuffer('main_redirect',$postaction,'POST');
		else
			{
			$this->updateDebugBuffer('main_redirect',$postaction,'POST',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$urlRedirect = $this->getElementString($res, '(&quot;', '&quot;)');
		$res = $this->get($urlRedirect, true);
		//check if we're in .
		if ($this->checkResponse("log_in",$res))
			$this->updateDebugBuffer('log_in',$urlRedirect,'GET');
		else
			{
			$this->updateDebugBuffer('log_in',$urlRedirect,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		//we are loged in. we must go to contacts page.
		$linkToAddressBook = "http://mail.yandex.ru/classic/abook";
		$this->login_ok = $linkToAddressBook;
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
		else $url = $this->login_ok;
		//parse the contacts list
		$res = $this->get($url, true);
		$res.="||exit||";
		$contacts = array();
		$temparr = array();
		while (strstr($res, 'href="compose?to='))
			{
			$res = $this->getElementString($res,'href="compose?to','||exit||');
			$res.= "||exit||";
			$temp = $this->getElementString($res,'=','"');
			array_push($temparr, ($temp));
			}
		foreach ($temparr as $k => $v)
			{
			$v = str_replace("%22", "", $v);
			$v = str_replace("%3E", "", $v);
			$v = str_replace("%20%3C", "|", $v);
			$v = str_replace("%20%20", " ", $v);
			$exp = explode("|", $v);
			$contacts[$exp[1]] = $exp[0];			
			}
		$this->showContacts=true;
		return $contacts;
		
	}
	public function logout()
	{
		//go to logout url.
		if ($this->login_ok)
		{
			$res = $this->get(urldecode("http://passport.yandex.ru/passport?mode=logout&retpath=http%3A%2F%2Fwww.yandex.ru%2F"),true);
			$this->debugRequest();
			$this->resetDebugger();
			$this->stopPlugin();
			return true;
		}
		else return false;
	}
}
?>