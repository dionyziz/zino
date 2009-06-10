<?php
    function ActionUserPasswordRequest( tInteger $requestid, tText $hash, tText $newpassword ) {
        global $libs;
        
        $userid = $userid->Get();
        $hash = $hash->Get();
        $user = New User( $userid );
        if ( !$user->Exists() ) {
            return Redirect( 'forgot/failure' );
        }
        
        $libs->Load( 'passwordrequest' );
        
        $request = New PasswordRequest( $requestid );
        if ( $request->Used || $request->Hash != $hash ) {
            return Redirect( 'forgot/failure' );
        }
        
        $request->Used = true;
        $request->Save();
        
        $myuser = New User( $userid );
        $myuser->UpdateLastLogin();
        $myuser->Save();
        $_SESSION[ 's_userid' ] = $myuser->Id;
        $_SESSION[ 's_authtoken' ] = $myuser->Authtoken;
        User_SetCookie( $myuser->Id, $myuser->Authtoken );
        
        return Redirect();
    }
?>
