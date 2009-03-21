<?php
    function UnitFrontpageNotificationNew( Notification $notif ) {
        die( 'plasma running' );
        ?>alert( 'notification creation to user <?php
        echo $notif->ToUser->Name;
        ?>' );<?php
        return $notif->ToUser->Id; 
    }
?>
