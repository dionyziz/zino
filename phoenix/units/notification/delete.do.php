<?php
	
	function UnitNotificationDelete( tInteger $eventid ) {
		global $user;
		global $libs;
		
		$libs->Load( 'notify' );
		$notif = New Notification( $eventid->Get() );
		
		if ( $notif->ToUser->Id == $user->Id ) {
			if ( $notif->Exists() ) {
				$notif->Delete();
			}
		}
	
	}
?>
