<?php
    function UnitFrontpageNotificationNew( Notification $notif ) {
        ?>var notifcontent = $( <?php
        ob_start();
        Element( 'notification/view' , $notif ); 
        echo w_json_encode( ob_get_clean() );
        ?> );
        Notification.AddNotif( notifcontent );<?php
 
        return $notif->ToUser->Id; 
    }
?>
