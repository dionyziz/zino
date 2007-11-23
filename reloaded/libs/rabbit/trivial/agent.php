<?php
	function GetUserBrowser() {
		global $userbrowser_name;
		global $userbrowser_version;
		
		$browser = array (
			"MSIE",            // parent
			"OPERA",
			"MOZILLA",        // parent
			"NETSCAPE",
			"FIREFOX",
			"SAFARI",
			"K-MELEON"
		);
		
		$info['browser'] = "OTHER";
		
		foreach ($browser as $parent) {
			$s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
			$f = $s + strlen($parent);
			$version = substr($_SERVER['HTTP_USER_AGENT'], $f, 5);
			$version = preg_replace('/[^0-9,.]/','',$version);
			
			if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent)) {
				$userbrowser_name = $parent;
				$userbrowser_version = $version;
			}
		}
		$userbrowser_detected = true;
	}
	
	function UserBrowser() {
		global $userbrowser_detected;
		global $userbrowser_name;
		
		if ( !$userbrowser_detected ) {
			GetUserBrowser();
		}
		return $userbrowser_name;
	}

	function UserIp() {
		if ( isset($_SERVER["HTTP_CLIENT_IP"]) ) {
			return $_SERVER["HTTP_CLIENT_IP"];
		} else {
			return $_SERVER["REMOTE_ADDR"];
		}	
	}
	function BrowserByUseragent( $useragent ) {
		$browser = array (
			"MSIE",            // parent
			"OPERA",
			"MOZILLA",        // parent
			"NETSCAPE",
			"FIREFOX",
			"SAFARI"
		);
		
		$info['browser'] = "OTHER";
		
        $userbrowser_name = '';
        $userbrowser_version = '0.0';
		foreach ($browser as $parent) {
			$s = strpos(strtoupper($useragent), $parent);
			$f = $s + strlen($parent);
			$version = substr($useragent, $f);
			$version = preg_replace('#[^0-9,.]#','',$version);
			
			if ( strpos(strtoupper($useragent), $parent)) {
				$userbrowser_name = $parent;
				$userbrowser_version = $version;
			}
		}
		return ucfirst( strtolower( $userbrowser_name ) ) . " " . $userbrowser_version;
	}
	
?>
