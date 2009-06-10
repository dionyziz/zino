<?php
    function ActionUserPasswordRecover( tInteger $requestid, tText $hash, tText $newpassword ) {
        global $libs;
        
        $requestid = $requestid->Get();
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
        
        return Redirect();
    }
?>
