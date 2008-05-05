<?php

	function UnitAlbumRename( tInteger $albumid , tString $albumname ) {
		global $user;
		
		$albumid = $albumid->Get();
		$albumname = $albumname->Get();
		$album = new Album( $albumid );
		if ( $album->User->Id == $user->Id ) {
			$album->Name = $albumname;
			$album->Save();
		}
	}
?>
