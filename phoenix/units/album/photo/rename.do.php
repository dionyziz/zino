<?php

	function UnitAlbumPhotoRename( tInteger $photoid , tString $photoname ) {
		global $user;
		
		$image = New Image( $photoid->Get() );
		if ( $image->User->Id == $user->Id ) {
			$image->Name = $photoname->Get();
			$image->Save();
		}
	}
?>
