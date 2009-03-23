<?php
    function UnitFrontpageNotificationNew( Notification $notif ) {
	?>var notifcontent = document.createElement( 'div' );
	$( notifcontent ).html( <?php
	ob_start();
	Element( 'notification/view' , $notif ); 
	echo w_json_encode( ob_get_clean() );
	?> );
	Frontpage.Notif.AddNotif( notifcontent );<?php
        return $notif->ToUser->Id; 
    }
?>
