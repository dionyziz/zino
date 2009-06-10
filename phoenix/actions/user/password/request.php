<?php
    function ActionUserPasswordRequest( tText $username ) {
        global $libs;
        
        $username = $username->Get();
        $userfinder = New UserFinder();
        $user = $userfinder->FindByUsername( $username );
        if ( !$user->Exists() || empty( $user->Profile->Email ) ) {
            return Redirect( 'forgot/failure' );
        }
        
        $libs->Load( 'passwordrequest' );
        
        $request = New PasswordRequest();
        $request->Userid = $user->Id;
        $request->Save();
        
        ob_start();
        $subject = Element( 'user/passwordrequest/mail' );
        $message = ob_get_clean();
        Email( $user->Name, $user->Profile->Email, $subject, $message, 'Zino', 'info@zino.gr' );
        
        return Redirect( 'forgot/success' );
    }
?>
