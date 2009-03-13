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
        $libs->Load( 'adminpanel/ban' );
        $loginattempt = New LoginAttempt();
        $loginattempt->Username = $username;
        if ( $user === false ) {
            $loginattempt->Password = $password;
            $loginattempt->Save();
            
            /*if ( LoginAttempt_checkBot( UserIp() ) ) {
                $ban = new Ban();
                $ban->BanIp( UserIp(), 15*60 );
            }*///TODO<--reconsider this

            return Redirect( '?p=a' );
        }
        $validate = $user->Profile->Emailvalidated;
        //if ( !$validate ) {
        //    return Redirect( '?p=notvalidated' );
        //}
        // don't store the password for security reasons
        $loginattempt->Success = 'yes';
        $loginattempt->Save();
        // else...
        $user->UpdateLastLogin();
        $user->Save();
        $_SESSION[ 's_userid' ] = $user->Id;
        $_SESSION[ 's_authtoken' ] = $user->Authtoken;
        User_SetCookie( $user->Id, $user->Authtoken );

        return Redirect( $_SERVER[ 'HTTP_REFERER' ]  );
    }
?>
