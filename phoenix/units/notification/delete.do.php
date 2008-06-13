<?php
	
	function UnitNotificationDelete( tInteger $eventid ) {
		global $user;
		
		$eventid = $eventid->Get();
		
		$notif = New Notification( $eventid->Get() );
		
		if ( $notif->ToUser->Id == $user->Id ) {
			if ( $notif->Exists() ) {
				$notif->Delete();
			}
		}
	
	}
?>
