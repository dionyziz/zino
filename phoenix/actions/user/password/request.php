<?php
    function ActionUserPasswordRequest( tText $username ) {
        global $libs;
        
        $username = $username->Get();
        $userfinder = New UserFinder();
        $user = $userfinder->FindByName( $username );
        if ( $user === false || empty( $user->Profile->Email ) ) {
            return Redirect( 'forgot/failure?username=' . $username );
        }
        
        $libs->Load( 'passwordrequest' );
        
        $request = New PasswordRequest();
        $request->Userid = $user->Id;
        $request->Save();
        
        ob_start();
        $subject = Element( 'user/passwordrequest/mail', $request->Id, $request->Hash );
        $message = ob_get_clean();
        Email( $user->Name, $user->Profile->Email, $subject, $message, 'Zino', 'info@zino.gr' );
        
        return Redirect( 'forgot/success' );
    }
?>
