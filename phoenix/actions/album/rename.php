<?php
	function ActionAlbumRename( tInteger $albumid , tString $albumname ) {
		global $user;
		
		$album = New Album( $albumid->Get() );
		if ( $album->User->Id == $user->Id ) {
			$album->Name = $albumname->Get();
			$album->Save();
		}
	}
?>
