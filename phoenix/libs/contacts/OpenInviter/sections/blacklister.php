<?php
/*
 * Created on Sep 3, 2008
 *
 * Owner: George
 */
class OpenInviter_Blacklister
	{
	
	public function OpenInviter_Blacklister()
		{
		include(dirname(__FILE__)."/../config.php");
		$this->username=$openinviter_settings['username'];
		$this->private_key=$openinviter_settings['private_key'];
		$this->transport=$openinviter_settings['transport'];
		}
	
	private function talk_to_server($signature,$xml)
		{
		if ($this->transport=='curl')
			{
			$ch=curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://api.openinviter.com/api/blacklister.php");
			curl_setopt($ch, CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_HTTPHEADER,Array("Content-Type:application/xml","X-Username: {$this->username}","X-Signature: {$signature}"));
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "{$xml}");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			$result=curl_exec($ch);
			curl_close($ch);
			return $result;
			}
		elseif ($this->transport=='wget')
			{
			$string_wget=" --timeout=5";
			$string_wget.=" --no-check-certificate";
			$string_wget.=" --header=\"Content-Type:application/xml\"";
			$string_wget.=" --header=\"X-Username: {$this->username}\"";
			$string_wget.=" --header=\"X-Signature: {$signature}\"";
			$url=escapeshellcmd($url);
			$string_wget.=" --post-data=\"{$xml}\"";
			$string_wget.=" --quiet --output-document=-";
			$command="wget {$string_wget} http://api.openinviter.com/api/blacklister.php";
			ob_start(); passthru($command,$return_var); $buffer = ob_get_contents(); ob_end_clean();
			if((strlen($buffer)==0)or($return_var!=0)) return(false);
			else return $buffer;
			}
		}
	
	public function request_blacklist($account,$domain)
		{
		$xml="<blacklist><email account='{$account}' domain='{$domain}' /></blacklist>";
		$signature = md5(md5($xml.$this->private_key).$this->private_key);
		$black_response=$this->talk_to_server($signature,$xml);
		if (!$black_response)
			return false;
		else
			{
			libxml_use_internal_errors(true);
			$parse_res=simplexml_load_string($black_response);
			libxml_use_internal_errors(false);
			if (!$parse_res)
				return false;
			if (empty($parse_res->error))
				return false;
			if ($parse_res->error['code']!=0)
				return false;
			}
		return true;
		}
	}
?>
