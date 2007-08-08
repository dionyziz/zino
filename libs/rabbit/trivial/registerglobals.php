<?php
	// Emulate register_globals off
	function registerglobals_off() {
		if ( !ini_get( 'register_globals' ) ) {
			return;
		}
		
		// Variables that shouldn't be unset
		$vars = array(
            'GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES', '_SESSION',
            'HTTP_SERVER_VARS', 'HTTP_GET_VARS', 'HTTP_COOKIE_VARS', 'HTTP_POST_FILES', 'HTTP_SESSION_VARS', 'HTTP_ENV_VARS'
        );

        foreach ( $vars as $global ) {
    		foreach ( $global as $key => $value ) {
    			if ( in_array( $key, $vars ) ) {
                    die( 'GLOBALS override attempt; bailing out' );
                }
                if ( isset( $GLOBALS[ $key ] ) ) {
    				unset( $GLOBALS[ $key ] );
    			}
    		}
        }
	}
?>
