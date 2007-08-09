<?php
    function ActionUserLogout() {
        global $user;
        global $rabbit_settings;
        
    	$_SESSION[ 's_username' ] = '';
    	$_SESSION[ 's_password' ] = '';

    	$user->RenewAuthtoken();
    	$user->SetCookie( true );

    	return Redirect( substr( $_SERVER[ 'HTTP_REFERER' ] , strlen( $rabbit_settings[ 'webaddress' ] . '/' ) ) );
    }
?>
