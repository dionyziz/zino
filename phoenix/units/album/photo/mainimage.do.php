<?php
	
	function UnitAlbumPhotoMainimage( tInteger $photoid ) {
		global $user;
		
		$photo = New Image( $photoid->Get() );
		
		if ( !$photo->IsDeleted() ) {
			if ( $photo->User->Id == $user->Id ) {
				$photo->Album->Mainimage = $photo->Id;
			}
		}	
	}
?>
