<?php
	
	function ElementNotificationList( $notifs ) {
		foreach( $notifs as $notif ) {
			Element( 'notification/view' , $notif );
		}
	}
?>
