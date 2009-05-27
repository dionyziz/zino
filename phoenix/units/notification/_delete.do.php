<?php
    
    function UnitNotificationDelete( tInteger $notificationid , tBoolean $relationnotif ) {
        global $user;
        global $libs;
        
        $notificationid = $notificationid->Get();
        $relationnotif = $relationnotif->Get();

        $libs->Load( 'notify' );
        
        $notif = New Notification( $notificationid );
        if ( $notif->Exists() ) {
            if ( $notif->ToUser->Id == $user->Id ) {
                $theuser = $notif->FromUser;
                $notif->Delete();
            }
        }
        if ( $relationnotif ) {
            ob_start();
            Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
            echo w_json_encode( ob_get_clean() );
            ?>;<?php
        }
    }
?>
