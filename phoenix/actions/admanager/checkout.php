<?php
    function ActionAdManagerCheckout(
        tInteger $numviews,
        tText $firstname,
        tText $lastname,
        tText $email,
        tText $payment
    ) {
        global $user;
        
        $numviews = $numviews->Get();
        $firstname = $firstname->Get();
        $lastname = $lastname->Get();
        $email = $email->Get();
        $payment = $payment->Get();
        
        $user->Profile->Firstname = $firstname;
        $user->Profile->Lastname = $lastname;
        if ( ValidEmail( $email ) ) {
            $user->Profile->Email = $email;                        
        }
        $user->Profile->Save();
        
        return Redirect( '?p=admanager/bank' );
    }
?>
