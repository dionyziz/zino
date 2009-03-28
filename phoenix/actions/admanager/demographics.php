<?php
    function ActionAdManagerDemographics(
        tInteger $adid, tInteger $minage, tInteger $maxage, tInteger $sex, tIntegerArray $places
    ) {
        global $libs;
        global $user;
        
        $libs->Load( 'admanager' );
        
        $adid = $adid->Get();
        
        // this user may be logged out; TODO
        if ( !$user->HasPermission( PERMISSION_AD_EDIT ) ) {
            return;
        }
        $ad = New Ad( $adid );
        if ( !$ad->Exists() ) {
            ?>Η διαφήμιση που προσπαθείτε να επεξεργαστείτε δεν υπάρχει.<?php
            return;
        }
        if ( $ad->Userid != $user->Id ) {
            ?>Δεν μπορείτε να επεξεργαστείτε μία διαφήμιση που δεν σας ανήκει.<?php
            return;
        }
        
        $ad->
        
        $ad->Save();
        
        return Redirect( '?p=admanager/list' );
    }
?>
