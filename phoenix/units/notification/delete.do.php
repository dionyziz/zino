<?php
	
	function UnitNotificationDelete( tInteger $eventid , tBoolean $relationnotif ) {
		global $user;
		global $libs;
		
		$libs->Load( 'notify' );
		$notif = New Notification( $eventid->Get() );
		$relationnotif = $relationnotif->Get();
		if ( $notif->Exists() ) {
			if ( $notif->ToUser->Id == $user->Id ) {
				$theuser = $notif->FromUser;
				$notif->Delete();
			}
		}
		if ( $relationnotif ) {
			?>document.location.href = <?php
			ob_start();
			Element( 'user/url' , $theuser );
			echo w_json_encode( ob_get_clean() );
			?>;<?php
		}
	}
?>
