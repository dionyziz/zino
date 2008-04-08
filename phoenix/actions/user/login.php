<?php
    function ActionUserLogin( tString $username, tString $password ) {
    	global $user;
        global $rabbit_settings;
    	global $water;
        
    	$s_username = $username->Get();
        $s_password = $password->Get();
    	
    	$_SESSION[ 's_password' ] = $s_password;
    	$_SESSION[ 's_username' ] = $s_username;
    	
        $finder = New UserFinder();
        $user = $finder->FindByNameAndPassword( $s_username, $s_password );
        
        if ( $user === false ) {
    		return Redirect( "?p=a" );
    	}
        // else...
        $user->UpdateLastLogin();
        $user->RenewAuthtoken();
        $user->Save();
        User_SetCookie( $user->Id, $user->Authtoken );

        return Redirect( $_SERVER[ 'HTTP_REFERER' ]  );
    }
?>
