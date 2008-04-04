<?php
	// Emulate register_globals off
	function registerglobals_off() {
		if ( !ini_get( 'register_globals' ) ) {
			return;
		}
		
        $global = registerglobal_getglobalnames();
        
        foreach ( $vars as $global ) {
            registerglobal_clearglobal( $global );
        }
	}
    
    function registerglobal_clearglobal( $name ) {
        $disallowed = registerglobal_getglobalnames();
        
        foreach ( $$global as $key => $value ) {
            if ( in_array( $key, $disallowed ) ) {
                die( 'GLOBALS override attempt; bailing out' );
            }
            if ( isset( $GLOBALS[ $key ] ) ) {
                unset( $GLOBALS[ $key ] );
            }
        }
    }
    
    function registerglobal_getglobalnames() {
		return array(
            'GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES', '_SESSION',
            'HTTP_SERVER_VARS', 'HTTP_GET_VARS', 'HTTP_COOKIE_VARS', 'HTTP_POST_FILES', 'HTTP_SESSION_VARS', 'HTTP_ENV_VARS'
        );
    }
?>
