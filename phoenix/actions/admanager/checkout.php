<?php
    function ActionAdManagerCheckout(
        tInteger $adid,
        tInteger $numviews,
        tText $firstname,
        tText $lastname,
        tText $email,
        tText $payment
    ) {
        global $user;
        global $libs;
        
        $libs->Load( 'admanager' );
        
        $numviews = $numviews->Get();
        $firstname = $firstname->Get();
        $lastname = $lastname->Get();
        $email = $email->Get();
        $payment = $payment->Get();
        $adid = $adid->Get();
        
        $ad = New Ad( $adid );
        if ( !$user->HasPermission( PERMISSION_AD_EDIT ) ) {
            ?>Δεν μπορείτε να επεξεργαστείτε διαφημίσεις.<?php
            return;
        }
        if ( !$ad->Exists() ) {
            ?>Η συγκεκριμένη διάφημιση δεν υπάρχει.<?php
            return;
        }
        if ( $ad->IsActive() ) {
            ?>Η συγκεκριμένη διάφημιση έχει ήδη ενεργοποιηθεί.<?php
            return;
        }
        if ( $ad->Userid != $user->Id ) {
            ?>Η συγκεκριμένη διάφημιση δεν σας ανήκει.<?php
            return;
        }
        $ad->Pageviewsremaining = $numviews;
        $ad->Save();
        
        $user->Profile->Firstname = $firstname;
        $user->Profile->Lastname = $lastname;
        if ( ValidEmail( $email ) ) {
            $user->Profile->Email = $email;                        
        }
        $user->Profile->Save();
        
        return Redirect( '?p=admanager/bank' );
    }
?>
