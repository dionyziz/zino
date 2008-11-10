<?php
$_pluginName='KataMail';
$_pluginVersion='1.0.0';
$_pluginDescription="Get the contacts from katamail.it";
$_requiredBaseVersion="1.5.0";
$_pluginType='email';
//imports email contacts from a katamail.it account.
//sends normal email.
class katamail extends OpenInviter_base
{
	private $login_ok=false;
	public $showContacts=false;
	private $server,$id = "";
	public $debug_array=array(
			  'main_redirect'=>'location.href'
	);
	public function login($user, $pass)
	{
		$this->resetDebugger();
		$this->service='katamail';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->curl=$this->init();
		//these are the variables sent by POST method
		$postvars = array(
			"Language"=>"italiano",
			"pop3host"=>"katamail.com",
			"username"=>$user,
			"LoginType"=>"xp",
			"language"=>"italiano",
			"MailType"=>"imap",
			"email"=>$user."@katamail.com",
			"password"=>$pass		);
		//Making a request to get initial cookie
		$res = $this->get("http://webmail.katamail.com", true);
		//Loging in with the post vars defined earlier
		$res = $this->post("http://webmail.katamail.com/atmail.php", $postvars, true);
		$res = htmlentities($res);
		//checking the first step
		if ($this->checkResponse("main_redirect",$res))
			$this->updateDebugBuffer('main_redirect',"http://webmail.katamail.com/atmail.php",'POST');
		else
			{
			$this->updateDebugBuffer('main_redirect',"http://webmail.katamail.com/atmail.php",'POST',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$this->login_ok = "http://webmail.katamail.com/abook.php?func=export&abookview=personal";
		return true;
	}
	public function getMyContacts()
	{
		//if loged in succes process contacts list... else stop pluging and display error message
		if (!$this->login_ok)
			{
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		else
			{
			$contacts = array();
			$res = $this->get($this->login_ok, true);
			$temp = explode("\n", $res);
			$i = 0;
			$count = count($temp);
			foreach ($temp as $v)
				{  
					$i++;
					if ($i == 1 or $i == $count) continue;
					$temp2 = explode(",",$v);
					$contacts[$temp2[1]] = $temp2[6]." ".$temp2[17];
				}
			}
		$this->showContacts = true;
		return $contacts;
	}
	public function logout()
	{
		//go to logout url
		if ($this->login_ok)
		{
			$logout_url = "http://webmail.katamail.com/util.php?func=logout";
			$res = $this->get($logout_url, true);
			$this->debugRequest();
			$this->resetDebugger();
			$this->stopPlugin();
			return true;
		}
		else return false;
	}
}
?>