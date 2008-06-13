<?php
	
	function UnitNotificationDelete( tInteger $eventid ) {
		global $user;
		global $libs;
		
		$libs->Load( 'notify' );
		$notif = New Notification( $eventid->Get() );
		
		if ( $notif->Exists() ) {
			if ( $notif->ToUser->Id == $user->Id ) {
				$notif->Delete();
			}
		}
	}
?>
