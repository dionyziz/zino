<?php
	
	function UnitAlbumDelete( tInteger $albumid ) {
		global $user;
		global $rabbit_settings;
		
		$albumid = $albumid->Get();
		$album = new Album( $albumid );
		if ( $album->User->Id == $user->Id ) {
			$useralbum = $album->User->Name;
			$album->Delete();
			?>document.location.href='<?php
			echo $rabbit_settings[ "webaddress" ] . "/?p=albums&username=" . $useralbum;
			?>';<?php
		}
	}
?>
