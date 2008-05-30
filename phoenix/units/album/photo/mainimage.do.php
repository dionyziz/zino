<?php
	
	function UnitAlbumPhotoMainimage( tInteger $photoid ) {
		global $user;
		
		$photo = New Image( $photoid->Get() );
		
		if ( !$photo->IsDeleted() ) {
			if ( $photo->User->Id == $user->Id ) {
				$photo->Album->Mainimage = $photo->Id;
				$photo->Album->Save();
				if ( $photo->Album->Id == $user->Egoalbumid ) {
					//when photo changes the avatar must be changed
					?>$( 'div.usersections a img' ).attr( {
						src : ExcaliburSettings.photosurl + '<?php
						echo $user->Id;
						?>/<?php
						echo $photo->Id;
						?>/<?php
						echo $photo->Id;
						?>_' + ExcaliburSettings.image_cropped_150x150 + '.jpg';
					} );<?php
				}
			}
		}	
	}
?>
