<?php
    function ActionUserLogout() {
        global $user;
        global $rabbit_settings;
        
    	$_SESSION[ 's_username' ] = '';
    	$_SESSION[ 's_password' ] = '';

    	$user->RenewAuthtoken();
    	$user->SetCookie( true );

    	return Redirect( $_SERVER[ 'HTTP_REFERER' ] );
    }
?>
