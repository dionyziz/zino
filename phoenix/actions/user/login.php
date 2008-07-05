<?php
    function ActionUserLogin( tText $username, tText $password ) {
    	global $user;
        global $rabbit_settings;
    	global $water;
    	
        $finder = New UserFinder();
        $user = $finder->FindByNameAndPassword( $username->Get(), $password->Get() );
        
        if ( $user === false ) {
    		return Redirect( "?p=a" );
    	}
        // else...
        $user->UpdateLastLogin();
        $user->RenewAuthtoken();
        $user->Save();
    	$_SESSION[ 's_userid' ] = $user->Id;
    	$_SESSION[ 's_authtoken' ] = $user->Authtoken;
        User_SetCookie( $user->Id, $user->Authtoken );

        return Redirect( $_SERVER[ 'HTTP_REFERER' ]  );
    }
?>
