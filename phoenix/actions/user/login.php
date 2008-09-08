<?php
    function ActionUserLogin( tText $username, tText $password ) {
        global $user;
        global $rabbit_settings;
        global $water;
        global $libs;
        
        $username = $username->Get();
        $password = $password->Get();
        $finder = New UserFinder();
        $user = $finder->FindByNameAndPassword( $username, $password );
        
        $libs->Load( 'loginattempt' );
        $loginattempt = New LoginAttempt();
        $loginattempt->Username = $username;
        if ( $user === false ) {
            $loginattempt->Password = $password;
            $loginattempt->Save();

            return Redirect( '?p=a' );
        }
        // don't store the password for security reasons
        $loginattempt->Success = 'yes';
        $loginattempt->Save();
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
