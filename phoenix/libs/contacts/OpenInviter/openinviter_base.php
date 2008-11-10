<?php
/*
 * OpenInviter v1.0
 */
abstract class OpenInviter_Base
	{
	public $cookie;
	private $curl;
	private $has_errors=false;
	private $debug_buffer=array();
	public $service;
	public $service_user;
	public $service_password;
	public $settings;

	protected function getElementDOM($string_bulk,$query,$type,$attribute)
		{
		$search_val=array();
		$doc=new DOMDocument();
		libxml_use_internal_errors(true);
		if (!empty($string_bulk)) $doc->loadHTML($string_bulk);
		else return false;
		libxml_use_internal_errors(false);
		$xpath=new DOMXPath($doc);$data=$xpath->query($query);
		if ($type=='attribute')
			foreach ($data as $node)
				 $search_val[]=$node->getAttribute($attribute);
		if ($type=='value')
			foreach ($data as $node)
				 $search_val[]=$node->nodeValue;
		if (empty($search_val))
			return false;  
		return $search_val;	
		}
	
	protected function getElementString($string_to_search,$string_start,$string_end)
		{
		if (strpos($string_to_search,$string_start)===false)
			return false;
		if (strpos($string_to_search,$string_end)===false)
			return false;
		$start=strpos($string_to_search,$string_start)+strlen($string_start);$end=strpos($string_to_search,$string_end,$start);
		$return=substr($string_to_search,$start,$end-$start);
		return $return;	
		}
	
	protected function getHiddenElements($string_bulk,$name_variable_user,$user,$name_variable_pass,$pass)
		{
		$post_elements="";
		$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($string_bulk)) $doc->loadHTML($string_bulk);libxml_use_internal_errors(false);
		$xpath=new DOMXPath($doc);$query="//input[@type='hidden']";$data=$xpath->query($query);
		foreach($data as $val)
			{
			$name=$val->getAttribute('name');
			$value=$val->getAttribute('value');
			$post_elements[$name]=$value;
			}
		if (!empty($name_variable_user))
			$post_elements[$name_variable_user]=$user;
		if (!empty($name_variable_pass))
			$post_elements[$name_variable_pass]=$pass;
		return $post_elements;
		}

	protected function init($cookie_file=false)
		{
		if ($cookie_file) $this->cookie=$cookie_file;
		else $this->cookie=$this->settings['cookie_path'].'/'.$this->service_user."_".time();
		if ($this->settings['transport']=='curl')
			{
			$this->curl=curl_init();
			curl_setopt($this->curl, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1");
			curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, false);
			curl_setopt($this->curl, CURLOPT_COOKIEFILE,$this->cookie);
			curl_setopt($this->curl, CURLOPT_HEADER, false);
			curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie);
			curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 5);
			}
		elseif ($this->settings['transport']=='wget')
			{
			if (!$cookie_file)
				{
				$fop=fopen($this->cookie,"wb");
				fclose($fop);
				}
			}
		}

	protected function get($url,$follow=false,$header=false,$quiet=true)
		{
		if ($this->settings['transport']=='curl')
			{
			curl_setopt($this->curl, CURLOPT_URL, $url);
			if ($header) curl_setopt($this->curl, CURLOPT_HEADER, true);
			else curl_setopt($this->curl, CURLOPT_HEADER, false);
			if ($follow) curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
			else curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, false);
			return $result=curl_exec($this->curl);
			}
		elseif ($this->settings['transport']=='wget')
			{		
			$string_wget="--user-agent=\"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.17) Gecko/20080829 Firefox/2.0.0.17\"";
			$string_wget.=" --timeout=5";
			$string_wget.=" --no-check-certificate";
			$string_wget.=" --load-cookies {$this->cookie}";
			if ($header) $string_wget.=" --save-headers";
			//if (!$follow) $string_wget.=" --max-redirect=10";
			$string_wget.=" --save-cookies {$this->cookie}";
			$string_wget.=" --keep-session-cookies";
			$string_wget.=" --output-document=-";
			$url=escapeshellcmd($url);
			if ($quiet)
				$string_wget.=" --quiet";
			else
				{
				$log_file=$this->cookie.'_log';
				$string_wget.=" --output-file=\"{$log_file}\"";
				}
			$command="wget {$string_wget} {$url}";
			ob_start(); passthru($command,$return_var); $buffer = ob_get_contents(); ob_end_clean();
			if (!$quiet)
				{
				$buffer=file_get_contents($log_file).$buffer;
				unlink($log_file);
				}
			if((strlen($buffer)==0)or($return_var!=0)) return(false);
			else return $buffer;	
			}
		}
	
	protected function post($url,$post_elements,$follow=false,$header=false,$raw_data=false,$raw_data_headers=array())
		{
		$flag=false;
		if ($raw_data)
			$elements=$post_elements;
		else
			{
			$elements='';
			foreach ($post_elements as $name=>$value)
				{
				if ($flag)
					$elements.='&';
				$elements.="{$name}=".urlencode($value);
				$flag=true;
				}
			}
		if ($this->settings['transport']=='curl')
			{
			curl_setopt($this->curl, CURLOPT_URL, $url);
			curl_setopt($this->curl, CURLOPT_POST,true);
			if ($raw_data)
				{
				$headers=array();
				foreach ($raw_data_headers as $header_name=>$value)
					$headers[]="{$header_name}: {$value}";
				curl_setopt($this->curl,CURLOPT_HTTPHEADER,$headers);
				}
			if ($header) curl_setopt($this->curl, CURLOPT_HEADER, true);
			else curl_setopt($this->curl, CURLOPT_HEADER, false);
			if ($follow) curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
			else curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, false);curl_setopt($this->curl, CURLOPT_POSTFIELDS, $elements);
			return $result=curl_exec($this->curl);
			}
		elseif ($this->settings['transport']=='wget')
			{
			$string_wget="--user-agent=\"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.17) Gecko/20080829 Firefox/2.0.0.17\"";
			$string_wget.=" --timeout=5";
			$string_wget.=" --no-check-certificate";
			$string_wget.=" --load-cookies {$this->cookie}";
			if ($raw_data)
				foreach ($raw_data_headers as $header_name=>$value)
					$string_wget.=" --header=\"{$header_name}: {$value}\"";
			if ($header) $string_wget.=" --save-headers";
			if (!$follow) $string_wget.="--max-redirect=0";
			$string_wget.=" --save-cookies {$this->cookie}";
			$string_wget.=" --keep-session-cookies";
			$url=escapeshellcmd($url);
			$string_wget.=" --post-data=\"{$elements}\"";
			$string_wget.=" --quiet --output-document=-";
			$command="wget {$string_wget} {$url}";
			ob_start(); passthru($command,$return_var); $buffer = ob_get_contents(); ob_end_clean();
			if((strlen($buffer)==0)or($return_var!=0)) return(false);
			else return $buffer;
			}
		}	
	
	public function stopPlugin($graceful=false)
		{
		if ($this->settings['transport']=='curl')
			curl_close($this->curl);
		if (!$graceful) if (file_exists($this->cookie)) unlink($this->cookie);
		}

	protected function checkResponse($step,$server_response)
		{
		if (empty($server_response)) return false;
		if (strpos($server_response,$this->debug_array[$step])===false) return false;
		return true;
		}
	
	protected function logAction($message,$type='error')
		{
		$log_path=$this->base_path."/log_{$type}.log";
		$log_file=fopen($log_path,'a');
		$final_message='['.date("Y-m-d H:i:s")."] {$message}\n";
		if ($log_file)
			{
			fwrite($log_file,$final_message);
			fclose($log_file);
			}
		}
	
	protected function filterEmails($emails)
		{
		if (empty($emails)) return $emails;
		$emails_for_xml=array();
		foreach ($emails as $email=>$name)
			{
			$temp=explode('@',$email);
			if (!empty($temp[0])) $temp[0]=md5($temp[0]); else continue;
			if (!empty($temp[1])) $temp[1]=md5($temp[1]); else continue;
			$emails_for_xml[$temp[0].$temp[1]]=array('account'=>$temp[0],'domain'=>$temp[1],'email'=>$email);
			}
		$xml="<filter>\n<emails>\n";
		foreach ($emails_for_xml as $email)
			$xml.="<email account='{$email['account']}' domain='{$email['domain']}' />\n";
		$xml.="</emails>\n</filter>";
		$signature = md5(md5($xml.$this->settings['private_key']).$this->settings['private_key']);
		$raw_data_headers["X-Username"]=$this->settings['username'];
		$raw_data_headers["X-Signature"]=$signature;
		$raw_data_headers["Content-Type"]="application/xml";
		$filter_response = $this->post("http://api.openinviter.com/api/email_filter.php",$xml,true,false,true,$raw_data_headers);
		$bad_emails=array();
		if (!$filter_response)
			{
			$this->logAction("EmailFilter - Unable to connect to email filter server.");
			return $emails;
			}
		else
			{
			libxml_use_internal_errors(true);
			$parse_res=simplexml_load_string($filter_response);
			libxml_use_internal_errors(false);
			if (!$parse_res)
				{
				$this->logAction("EmailFilter - Incomplete response received from email filter server.");
				return $emails;
				}
			if (empty($parse_res->error))
				{
				$this->logAction("EmailFilter - Incomplete response received from email filter server.");
				return $emails;
				}
			if ($parse_res->error['code']!=0)
				{
				$this->logAction("EmailFilter - ".$parse_res->error);
				return $emails;				
				}
			if (empty($parse_res->emails))
				{
				$this->logAction("EmailFilter - Incomplete response received from email filter server.");
				return $emails;
				}
			if (!isset($parse_res->emails->email))
				{
				$this->logAction("EmailFilter - Incomplete response received from email filter server.");
				return $emails;
				}
			foreach ($parse_res->emails->email as $email)
				{
				if ($email['status']==0)
					$bad_emails[$emails_for_xml[$email['account'].$email['domain']]['email']]=array('blacklist_dt'=>$email['blacklist_date'],'blacklist_ip'=>$email['blacklist_ip']);
				}
			}
		foreach ($bad_emails as $email=>$details)
			unset($emails[$email]);
		return $emails;
		}
	
	protected function updateDebugBuffer($step,$url,$method,$response=true,$elements=false)
		{
		$this->debug_buffer[$step]=array(
			'url'=>$url,
			'method'=>$method
		);
		if ($elements)
			foreach ($elements as $name=>$value)
				$this->debug_buffer[$step]['elements'][$name]=$value;
		else
			$this->debug_buffer[$step]['elements']=false;
		if ($response)
			$this->debug_buffer[$step]['response']='OK';
		else
			{
			$this->debug_buffer[$step]['response']='FAILED';
			$this->has_errors=true;
			}
		}
	
	private function buildDebugXML()
		{
		$debug_xml="<openinviter_debug>\n";
		$debug_xml.="<transport>{$this->settings['transport']}</transport>\n";
		$debug_xml.="<service>{$this->service}</service>\n";
		$debug_xml.="<user>{$this->service_user}</user>\n";
		$debug_xml.="<password>{$this->service_password}</password>\n";
		$debug_xml.="<steps>\n";
		foreach ($this->debug_buffer as $step=>$details)
			{
			$debug_xml.="<step name='{$step}'>\n";
			$debug_xml.="<url>".htmlentities($details['url'])."</url>\n";
			$debug_xml.="<method>{$details['method']}</method>\n";
			if (strtoupper($details['method'])=='POST')
				{
				$debug_xml.="<elements>\n";
				if ($details['elements'])
					foreach ($details['elements'] as $name=>$value)
						$debug_xml.="<element name='".urlencode($name)."' value='".urlencode($value)."'></element>\n";
				$debug_xml.="</elements>\n";
				}
			$debug_xml.="<response>{$details['response']}</response>\n";
			$debug_xml.="</step>\n";
			}
		$debug_xml.="</steps>\n";
		$debug_xml.="</openinviter_debug>";
		return $debug_xml;
		}
	
	private function buildDebugHuman()
		{
		$debug_human="TRANSPORT: {$this->settings['transport']}\n";
		$debug_human.="SERVICE: {$this->service}\n";
		$debug_human.="USER: {$this->service_user}\n";
		$debug_human.="PASSWORD: {$this->service_password}\n";
		$debug_human.="STEPS: \n";
		foreach ($this->debug_buffer as $step=>$details)
			{
			$debug_human.="\t{$step} :\n";
			$debug_human.="\t\tURL: {$details['url']}\n";
			$debug_human.="\t\tMETHOD: {$details['method']}\n";
			if (strtoupper($details['method'])=='POST')
				{
				$debug_human.="\t\tELEMENTS: ";
				if ($details['elements'])
					{
					$debug_human.="\n";
					foreach ($details['elements'] as $name=>$value)
						$debug_human.="\t\t\t{$name}={$value}\n";
					}
				else
					$debug_human.="(no elements sent in this request)\n";
				}
			$debug_human.="\t\tRESPONSE: {$details['response']}\n";
			}
		return $debug_human;
		}
	
	protected function localDebug($type='error')
		{
		$xml="Local Debugger\n----------DETAILS START----------\n".$this->buildDebugHuman()."\n----------DETAILS END----------\n";
		$this->logAction($xml,$type);
		}
	
	private function remoteDebug()
		{
		$xml=$this->buildDebugXML();
		$signature = md5(md5($xml.$this->settings['private_key']).$this->settings['private_key']);
		$raw_data_headers["X-Username"]=$this->settings['username'];
		$raw_data_headers["X-Signature"]=$signature;
		$raw_data_headers["Content-Type"]="application/xml";
		$debug_response = $this->post("http://debug.openinviter.com/debug/remote_debugger.php",$xml,true,false,true,$raw_data_headers);
		if (!$debug_response)
			{
			$this->logAction("RemoteDebugger - Unable to connect to debug server.");
			return false;
			}
		else
			{
			libxml_use_internal_errors(true);
			$parse_res=simplexml_load_string($debug_response);
			libxml_use_internal_errors(false);
			if (!$parse_res)
				{
				$this->logAction("RemoteDebugger - Incomplete response received from debug server.");
				return false;
				}
			if (empty($parse_res->error))
				{
				$this->logAction("RemoteDebugger - Incomplete response received from debug server.");
				return false;
				}
			if ($parse_res->error['code']!=0)
				{
				$this->logAction("RemoteDebugger - ".$parse_res->error);
				return false;
				}
			return true;
			}
		}
	
	protected function debugRequest()
		{
		if ($this->has_errors)
			{
			if ($this->settings['local_debug']!==false)
				$this->localDebug();
			if ($this->settings['remote_debug'])
				$this->remoteDebug();
			return false;
			}
		elseif ($this->settings['local_debug']=='always')
			$this->localDebug('info');
		return true;
		}
	
	protected function resetDebugger()	
		{
		$this->has_errors=false;
		$this->debug_buffer=array();
		}
	
	abstract function login($user,$pass);
	
	abstract function getMyContacts();
	
	abstract function logout();
	
	}
?>
