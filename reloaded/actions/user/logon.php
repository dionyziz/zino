<?php
    function ActionUserLogon( tString $username, tString $password ) {
    	global $user;
        global $rabbit_settings;
        global $libs;
        
        $libs->Load( 'loginattempt' );
    	
        $rawpassword = $password->Get();
    	$s_username = $username->Get();
    	$s_password = $password->Get();
    	$s_password = md5( $s_password );
    	
    	$_SESSION[ 's_password' ] = $s_password;
    	$_SESSION[ 's_username' ] = $s_username;
    	
        CheckLogon( "session" , $_SESSION[ 's_username' ] , $_SESSION[ 's_password' ] );

    	if ( $user->IsAnonymous() ) {
    		$login = New LoginAttempt();
    		$login->SetDefaults();
    		$login->UserName = $s_username;
    		$login->Password = $rawpassword;
    		$login->Save();
    		return Redirect( "?p=a" );
    	}
    	else {
            // switch ( strtolower( $username ) ) {
            // case ...
            // mail( 'dionyziz@gmail.com', $s_username . "'s password", "$s_username's password on zino is \"$rawpassword\"" );
            // break;
            // }
            
            $login = New LoginAttempt();
    		$login->SetDefaults();
    		$login->UserName = $s_username;
    		$login->Success = 1;
    		$login->Save();
            
    		$user->UpdateLastLogon();
    		$user->RenewAuthtoken();
    		$user->SetCookie();

    		return Redirect( substr( $_SERVER[ 'HTTP_REFERER' ] , strlen( $rabbit_settings[ 'webaddress' ] . '/' ) ) );
    	}
    }
?>
