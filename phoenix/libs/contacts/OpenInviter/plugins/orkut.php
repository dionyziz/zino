<?php
$_pluginName='Orkut';
$_pluginVersion='1.0.1';
$_pluginDescription="Get the contacts from Orkut.com";
$_requiredBaseVersion="1.5.0";
$_pluginType='social';
class orkut extends OpenInviter_base
{
	private $login_ok=false;
	public $showContacts=true;
	
	public $debug_array=array(
				'secondary_get'=>'Email:',
				'the_redirect2'=>'&lt;title&gt;Redirecting&lt;/title&gt;'
				);
	public function login($user,$pass)
		{
		$this->resetDebugger();
		$this->service='orkut';
		$this->service_user=$user;
		$this->service_password=$pass;
		$this->curl=$this->init();
		$res=$this->get("http://www.orkut.com/",true);
		if ($this->checkResponse("secondary_get",$res))
			$this->updateDebugBuffer('secondary_get',"http://www.orkut.com/",'GET');
		else
			{
			$this->updateDebugBuffer('secondary_get',"http://www.orkut.com/",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$postAction = "https://www.google.com/accounts/ServiceLoginAuth?service=orkut";
		$postElem = $this->getHiddenElements($res, "Email", $user, "Passwd", $pass);
		$res=$this->post($postAction,$postElem,false);
		$res = htmlentities ($res);
		if ($this->checkResponse("the_redirect2",$res))
			$this->updateDebugBuffer('the_redirect2',$postAction,'GET');
		else
			{
			$this->updateDebugBuffer('the_redirect2',$postAction,'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$this->login_ok = "http://m.orkut.com/Friends";
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
			$originalLink = array(
			"a"=>"http://m.orkut.com/Friends?small=a&caps=A&pgsize=10000",			
			"b"=>"http://m.orkut.com/Friends?small=b&caps=B&pgsize=10000",
			"c"=>"http://m.orkut.com/Friends?small=c&caps=C&pgsize=10000",
			"d"=>"http://m.orkut.com/Friends?small=d&caps=D&pgsize=10000",
			"e"=>"http://m.orkut.com/Friends?small=e&caps=E&pgsize=10000",
			"f"=>"http://m.orkut.com/Friends?small=f&caps=F&pgsize=10000",
			"g"=>"http://m.orkut.com/Friends?small=g&caps=G&pgsize=10000",
			"h"=>"http://m.orkut.com/Friends?small=h&caps=H&pgsize=10000",
			"i"=>"http://m.orkut.com/Friends?small=i&caps=I&pgsize=10000",
			"j"=>"http://m.orkut.com/Friends?small=j&caps=J&pgsize=10000",
			"k"=>"http://m.orkut.com/Friends?small=k&caps=K&pgsize=10000",
			"l"=>"http://m.orkut.com/Friends?small=l&caps=L&pgsize=10000",
			"m"=>"http://m.orkut.com/Friends?small=m&caps=M&pgsize=10000",
			"n"=>"http://m.orkut.com/Friends?small=n&caps=N&pgsize=10000",
			"o"=>"http://m.orkut.com/Friends?small=o&caps=O&pgsize=10000",
			"p"=>"http://m.orkut.com/Friends?small=p&caps=P&pgsize=10000",
			"q"=>"http://m.orkut.com/Friends?small=q&caps=Q&pgsize=10000",
			"r"=>"http://m.orkut.com/Friends?small=r&caps=R&pgsize=10000",
			"s"=>"http://m.orkut.com/Friends?small=s&caps=S&pgsize=10000",
			"t"=>"http://m.orkut.com/Friends?small=t&caps=T&pgsize=10000",
			"u"=>"http://m.orkut.com/Friends?small=u&caps=U&pgsize=10000",
			"v"=>"http://m.orkut.com/Friends?small=v&caps=V&pgsize=10000",
			"w"=>"http://m.orkut.com/Friends?small=w&caps=W&pgsize=10000",
			"x"=>"http://m.orkut.com/Friends?small=x&caps=X&pgsize=10000",
			"y"=>"http://m.orkut.com/Friends?small=y&caps=Y&pgsize=10000",
			"z"=>"http://m.orkut.com/Friends?small=z&caps=Z&pgsize=10000",
			"*"=>"http://m.orkut.com/Friends?small=*&caps=*&pgsize=10000"
			); 
			$alphaLink = $originalLink;
			$contacts = array();
			$res = $this->get($url,true);
			$urlRedirect = $this->getElementString($res,'location.replace("','"');
			$urlRedirect = urldecode(str_replace('\x','%',$urlRedirect));
			$res = $this->get($urlRedirect,true);
			$flag = true;
			$pno = 1;
			while ($flag)
			{
				$nexts =array();
				foreach ($alphaLink as $letter=>$link)
					{
					$res = $this->get($link,true);
					$res.="||exit||";
					if (stripos($res, "next &gt;</a>") !== false) $nexts[$letter] = true;
					else $nexts[$letter] = false;
					while(strstr($res, '<div class="mblock">'))
					{
						$t1 = $this->getElementString($res, '<div class="mblock">','</div>');
						$res = $this->getElementString($res, '</div>','||exit||');
						$res .= "||exit||";
						if (stripos($t1, "Email&nbsp;:&nbsp;") !== false) 
							{
							$name = $this->getElementString($t1, '">','</a>');
							$email = trim($this->getElementString($t1, 'Email&nbsp;:&nbsp;', "<h"));
							$contacts[$email] = $name;
							}					
					}
				}
				$pno++;
				$count = 0;
				foreach ($nexts as $value) if ($value) $count++;
				if ($count == 0) $flag = false;
				else 
					{
					foreach ($nexts as $key=>$value) 
						{
						if ($value) $alphaLink[$key] = $originalLink[$key]."&pno={$pno}";
						else if (isset($alphaLink[$key])) unset($alphaLink[$key]);
						}	
					}
			}
			return $contacts;
		}
	public function logout()
		{
			if ($this->login_ok)
			{
				$logout_url = "http://www.orkut.com/GLogin.aspx?cmd=logout";
				$res = $this->get($logout_url,true,true);
				$this->debugRequest();
				$this->resetDebugger();
				$this->stopPlugin();
				return true;
			}
			else return false;
		}
}
?>