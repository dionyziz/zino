<?php
    function ActionUserPasswordRecover( tInteger $requestid, tText $hash, tText $password ) {
        global $libs;
        
        $requestid = $requestid->Get();
        $hash = $hash->Get();
        
        $libs->Load( 'passwordrequest' );
        
        $request = New PasswordRequest( $requestid );
        if ( $request->Used ) {
            return Redirect( 'forgot/failure?used' );
        }
        if ( $request->Hash != $hash ){
            return Redirect( 'forgot/failure?hash' );
        }
        
        $request->Used = true;
        $request->Save();
        
        $user->Password = $password;
        $user->Save();
        
        return Redirect( '' );
    }
?>
