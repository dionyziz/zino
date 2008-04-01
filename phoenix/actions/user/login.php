<?php
    function ActionUserLogin( tString $username, tString $password ) {
    	global $user;
        global $rabbit_settings;
    	
        $rawpassword = $password->Get();
    	$s_username = $username->Get();
    	$s_password = $password->Get();
    	$s_password = md5( $s_password );
    	
    	$_SESSION[ 's_password' ] = $s_password;
    	$_SESSION[ 's_username' ] = $s_username;
    	
        $finder = New UserFinder();
        $user = $finder->FindByNameAndPassword( $_SESSION[ 's_username' ] , $_SESSION[ 's_password' ] );
    	
        if ( !$user->Exists() ) {
    		return Redirect( "?p=a" );
    	}
        // else...
        $user->UpdateLastLogon();
        $user->RenewAuthtoken();
        $user->Save();
        User_SetCookie( $user->Id, $user->Authtoken );

        return Redirect( $_SERVER[ 'HTTP_REFERER' ]  );
    }
?>
