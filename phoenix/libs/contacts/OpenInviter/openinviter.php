<?php
/**
 * The core of the OpenInviter system
 * 
 * Contains methods and properties used by all
 * the OpenInivter plugins
 * 
 * @author OpenInviter
 * @version 1.6.7
 */
class OpenInviter
	{
	public $pluginTypes=array('email'=>'Email Providers','social'=>'Social Networks');
	private $ignoredFiles=array('default.php'=>'','index.php'=>'');
	private $version='1.6.7';
	public function __construct()
		{
		include(dirname(__FILE__)."/config.php");
		include_once(dirname(__FILE__)."/openinviter_base.php");
		$this->settings=$openinviter_settings;
		}
	
	/**
	 * Start internal plugin
	 * 
	 * Starts the internal plugin and
	 * transfers the settings to it.
	 * 
	 * @param string $plugin_name The name of the plugin being started
	 */	  
	public function startPlugin($plugin_name)
		{
		if (file_exists(dirname(__FILE__)."/postinstall.php"))
			$this->internalError="You have to delete postinstall.php before using OpenInviter";
		elseif (file_exists(dirname(__FILE__)."/plugins/{$plugin_name}.php"))
			{
			$ok=true;
			if (!class_exists($plugin_name)) include_once(dirname(__FILE__)."/plugins/{$plugin_name}.php");
			$this->plugin=new $plugin_name();
    		$this->plugin->settings=$this->settings;
    		$this->plugin->base_version=$this->version;
    		$this->plugin->base_path=dirname(__FILE__);
			}
		else
			$this->internalError="Invalid service provider";
		}
	
	/**
	 * Stop the internal plugin
	 * 
	 * Acts as a wrapper function for the stopPlugin
	 * function in the OpenInviter_Base class
	 */
	public function stopPlugin($graceful=false)
		{
		$this->plugin->stopPlugin($graceful);
		}

	/**
	 * Login function
	 * 
	 * Acts as a wrapper function for the plugin's
	 * login function.
	 * 
	 * @param string $user The username being logged in
	 * @param string $pass The password for the username being logged in
	 * @return mixed FALSE if the login credentials don't match the plugin's requirements or the result of the plugin's login function.
	 */
	public function login($user,$pass)
		{
		if (!$this->checkLoginCredentials($user)) return false;
		return $this->plugin->login($user,$pass);
		}
	
	/**
	 * Get the current user's contacts
	 * 
	 * Acts as a wrapper function for the plugin's
	 * getMyContacts function.
	 * 
	 * @return mixed The result of the plugin's getMyContacts function.
	 */
	public function getMyContacts()
		{
		return $this->plugin->getMyContacts();
		}	

	/**
	 * End the current user's session
	 * 
	 * Acts as a wrapper function for the plugin's
	 * logout function
	 * 
	 * @return bool The result of the plugin's logout function.
	 */
	public function logout()
		{
		return $this->plugin->logout();	
		}

	/**
	 * Get the installed plugins
	 * 
	 * Returns information about the available plugins
	 * 
	 * @return mixed An array of the plugins available or FALSE if there are no plugins available.
	 */
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
		        if ($this->checkVersion($_pluginInfo['base_version']))
		       		$plugins[$_pluginInfo['type']][$plugin_key]=$_pluginInfo;
				}
			}
		if (count($plugins)>0) return $plugins;
		else return false;
		}
	
	/**
	 * Send a message
	 * 
	 * Acts as a wrapper for the plugin's
	 * sendMessage function.
	 * 
	 * @param string $session_id The OpenInviter user's session ID
	 * @param string $message The message being sent to the users
	 * @param array $contacts An array of contacts that are going to receive the message
	 * @return mixed -1 if the plugin doesn't have an internal sendMessage function or the result of the plugin's sendMessage function
	 */
	public function sendMessage($session_id,$message,$contacts)
		{
		$this->plugin->init($session_id);
		$internal=$this->getInternalError();
		if ($internal) return false;
		if (!method_exists($this->plugin,'sendMessage')) return -1;
		else return $this->plugin->sendMessage($session_id,$message,$contacts);
		}
	
	/**
	 * Find out if the contacts should be displayed
	 * 
	 * Tells whether the current plugin will display
	 * a list of contacts or not
	 * 
	 * @return bool TRUE if the plugin displays the list of contacts, FALSE otherwise.
	 */
	public function showContacts()
		{
		return $this->plugin->showContacts;
		}
	
	/**
	 * Check version requirements
	 * 
	 * Checks if the current version of OpenInviter
	 * is greater than the plugin's required version
	 * 
	 * @param string $required_version The OpenInviter version that the plugin requires.
	 * @return bool TRUE if the version if equal or greater, FALSE otherwise.
	 */
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
	
	/**
	 * Find out the version of OpenInviter
	 * 
	 * Find out the version of the OpenInviter
	 * base class
	 * 
	 * @return string The version of the OpenInviter base class.
	 */
	public function getVersion()
		{
		return $this->version;
		}
	
	/**
	 * Check the provided login credentials
	 * 
	 * Checks whether the provided login credentials
	 * match the plugin's required structure and (if required)
	 * if the provided domain name is allowed for the
	 * current plugin.
	 * 
	 * @param string $user The provided user name.
	 * @return bool TRUE if the login credentials match the required structure, FALSE otherwise. 
	 */
	private function checkLoginCredentials($user)
		{
		$is_email=$this->plugin->isEmail($user);
		if ($this->plugin->requirement)
			{
			if ($this->plugin->requirement=='email' AND !$is_email)
				{
				$this->internalError="Please enter the full email, not just the username";
				return false;
				}
			elseif ($this->plugin->requirement=='user' AND $is_email)
				{
				$this->internalError="Please enter just the username, not the full email";
				return false;
				}
			}
		if ($this->plugin->allowed_domains AND $is_email)
			{
			$temp=explode('@',$user);$user_domain=$temp[1];$temp=false;
			foreach ($this->plugin->allowed_domains as $domain)
				if (strpos($user_domain,$domain)!==false) $temp=true;
			if (!$temp)
				{
				$this->internalError="<b>{$user_domain}</b> is not a valid domain for this provider";
				return false;
				}
			}
		return true;
		}
	
	/**
	 * Gets the OpenInviter's internal error
	 * 
	 * Gets the OpenInviter's base class or the plugin's
	 * internal error message
	 * 
	 * @return mixed The error message or FALSE if there is no error.s
	 */
	public function getInternalError()
		{
		if (isset($this->internalError)) return $this->internalError;
		if (isset($this->plugin->internalError)) return $this->plugin->internalError;
		return false;
		}
	
	/**
	 * Get the current OpenInviter session ID
	 * 
	 * Acts as a wrapper function for the plugin's
	 * getSessionID function.
	 * 
	 * @return mixed The result of the plugin's getSessionID function.
	 */
	public function getSessionID()
		{
		return $this->plugin->getSessionID();
		}
	
	}
	
	
?>
