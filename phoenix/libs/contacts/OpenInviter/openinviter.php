<?php
/*
 * OpenInviter v1.0
 */
class OpenInviter
	{
	public $pluginTypes=array('email'=>'Email Providers','social'=>'Social Networks');
	private $ignoredFiles=array('default.php'=>'','index.php'=>'');
	private $version='1.5.1';
	public function __construct()
		{
		include(dirname(__FILE__)."/config.php");
		include_once(dirname(__FILE__)."/openinviter_base.php");
		$this->settings=$openinviter_settings;
		}
	
	//this is the constructor part it create an new internal object in the class so you can use it as a plugin  
	public function startPlugin($plugin_name)
		{
		if (file_exists(dirname(__FILE__)."/plugins/{$plugin_name}.php"))
			{
			$ok=true;
			if (!class_exists($plugin_name)) include_once(dirname(__FILE__)."/plugins/{$plugin_name}.php");
    		$this->plugin=new $plugin_name();
    		$this->plugin->settings=$this->settings;
    		$this->plugin->base_path=dirname(__FILE__);
			}
		else
	//TODO: use another method unset($this) doesn't work. Try return false; here or use a public variable to check the state of the object.
			unset($this);
		}
	
	public function stopPlugin($graceful=false)
		{
		$this->plugin->stopPlugin($graceful);
		}

	public function login($user,$pass)
		{
		return $this->plugin->login($user,$pass);
		}
		
	public function getMyContacts()
		{
		return $this->plugin->getMyContacts();
		}	

	public function logout()
		{
		return $this->plugin->logout();	
		}
	
	public function debug()
		{
		return $this->plugin->debug();	
		}
	
	public function getPlugins()
		{
		$plugins=array();$array_file=array();
		$dir=dirname(__FILE__)."/plugins";
		if (is_dir($dir)) 
		    if ($op=opendir($dir))
		    	{ 
		        while (false!==($file=readdir($op))) 
		        	if (($file!=".") AND ($file!="..") AND (strpos($file,'.php')!==false) AND (!isset($this->ignoredFiles[$file]))) $array_file[$file]=$file;
		        closedir($op);
		    	}
		if (count($array_file)>0) 
			{
			sort($array_file);
			foreach($array_file as $key=>$val)
				{
		    	$plugin_key=str_replace('.php','',$val);
		        include("{$dir}/{$val}");
		        if ($this->checkVersion($_requiredBaseVersion))
		       		$plugins[$_pluginType][$plugin_key]=array('name'=>$_pluginName,'version'=>$_pluginVersion,'description'=>$_pluginDescription);
				}
			}
		if (count($plugins)>0) return $plugins;
		else return false;
		}
	
	public function sendMessage($cookie_file,$message,$contacts)
		{
		if (!method_exists($this->plugin,'sendMessage')) return -1;
		else return $this->plugin->sendMessage($cookie_file,$message,$contacts);
		}
	
	public function showContacts()
		{
		return $this->plugin->showContacts;
		}
	
	public function checkVersion($required_version)
		{
		$temp_required=explode('.',$required_version);
		if (count($temp_required)!=3)
			return false;
		$temp=explode('.',$this->version);
		foreach ($temp as $key=>$value)
			if ($temp_required[$key]>$value)
				return false;
		return true;
		}
	
	public function getVersion()
		{
		return $this->version;
		}
	
	}
	
	
?>
