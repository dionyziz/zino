<?php
$base_path=dirname(__FILE__);
include("{$base_path}/config.php");
$username=$openinviter_settings['username'];
$private_key=$openinviter_settings['private_key'];
$transport=$openinviter_settings['transport'];
function talk_to_server($signature,$xml)
	{
	global $transport,$username;
	if ($transport=='curl')
		{
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://update.openinviter.com/updater/auto_updater.php");
		curl_setopt($ch, CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_HTTPHEADER,Array("Content-Type:application/xml","X-Username: {$username}","X-Signature: {$signature}"));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "{$xml}");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$result=curl_exec($ch);
		curl_close($ch);
		return $result;
		}
	elseif ($transport=='wget')
		{
		$string_wget=" --timeout=5";
		$string_wget.=" --no-check-certificate";
		$string_wget.=" --header=\"Content-Type:application/xml\"";
		$string_wget.=" --header=\"X-Username: {$username}\"";
		$string_wget.=" --header=\"X-Signature: {$signature}\"";
		$url=escapeshellcmd($url);
		$string_wget.=" --post-data=\"{$xml}\"";
		$string_wget.=" --quiet --output-document=-";
		$command="wget {$string_wget} http://update.openinviter.com/updater/auto_updater.php";
		ob_start(); passthru($command,$return_var); $buffer = ob_get_contents(); ob_end_clean();
		if((strlen($buffer)==0)or($return_var!=0)) return(false);
		else return $buffer;
		}
	}

function log_action($message,$type='error')
	{
	global $base_path;
	$log_path="{$base_path}/log_{$type}.log";
	$log_file=fopen($log_path,'a');
	$final_message='['.date("Y-m-d H:i:s")."] {$message}\n";
	if ($log_file)
		{
		fwrite($log_file,$final_message);
		fclose($log_file);
		}
	}

$openinviter_md5=md5(file_get_contents("{$base_path}/openinviter.php"));
$openinviter_base_md5=md5(file_get_contents("{$base_path}/openinviter_base.php"));

include("{$base_path}/openinviter.php");
$inviter=new OpenInviter();
$plugins=$inviter->getPlugins();
$plugin_md5s=array();
foreach ($plugins as $plugin=>$dummy)
	$plugin_md5s[$plugin]=md5(file_get_contents("{$base_path}/plugins/{$plugin}.php"));

$xml="<openinviter_updater operation='file_check'>
<file path='openinviter.php' checksum='{$openinviter_md5}'></file>
<file path='openinviter_base.php' checksum='{$openinviter_base_md5}'></file>\n";
foreach ($plugin_md5s as $plugin=>$md5)
	$xml.="<file path='plugins/{$plugin}.php' checksum='{$md5}'></file>\n";
$xml.="</openinviter_updater>";
$signature = md5(md5($xml.$private_key).$private_key);

$file_check=talk_to_server($signature,$xml);

if (!$file_check)
	{
	log_action("AutoUpdater - Unable to connect to update server.");
	exit;
	}
echo $file_check;exit;
libxml_use_internal_errors(true);
$parsed_xml=simplexml_load_string($file_check);
libxml_use_internal_errors(false);

if (!$parsed_xml)
	{
	log_action("AutoUpdater - Incomplete server response.");
	exit;
	}
if (empty($parsed_xml->error))
	{
	log_action("AutoUpdater - Incomplete server response.");
	exit;
	}
if ($parsed_xml->error['code']!=0)
	{
	log_action("AutoUpdater - ".$parsed_xml->error);
	exit;				
	}
if (empty($parsed_xml->update_status))
	{
	log_action("AutoUpdater - Incomplete server response.");
	exit;
	}
if ($parsed_xml->update_status['code']==0)
	exit;
if (!isset($parsed_xml->file))
	{
	log_action("AutoUpdater - Incomplete server response.");
	exit;
	}

$must_update=array();
foreach ($parsed_xml->file as $file)
	$must_update[(string)$file['path']]=(string)$file['type'];

foreach ($must_update as $file=>$type)
	{
	$xml="<openinviter_updater operation='get_file'><file path='{$file}'></file></openinviter_updater>";
	$signature = md5(md5($xml.$private_key).$private_key);
	$file_contents=talk_to_server($signature,$xml);
//	if (!$file_contents)
//		log_action("AutoUpdater - Failed to update {$file}");
//	else
//		{
//		file_put_contents("{$base_path}/{$file}",$file_contents);
//		log_action("AutoUpdater - Updated {$file}",'info');
//		}
echo $file_contents."<BR><BR><BR><BR><BR>";
	}

?>
