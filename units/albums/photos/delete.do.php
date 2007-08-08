<?php
	function UnitAlbumsPhotosDelete( tInteger $photoid ) {
		global $photo;
		global $libs;
		global $user;
        
        $photoid = $photoid->Get();
        
		$libs->Load( 'image/image' );
		$photo = New Image( $photoid );
		
		if ( $photo->UserId() == $user->Id() || $user->CanModifyCategories() ) {
			$photo->Delete();
		}
	}
?>