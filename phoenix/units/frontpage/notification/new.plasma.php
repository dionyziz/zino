<?php
    function UnitFrontpageNotificationNew( Notification $notif ) {
        ?>alert( 'notification creation to user <?php
        echo $notif->ToUser->Name;
        ?>' );<?php
        return $notif->ToUser->Id; 
    }
?>
