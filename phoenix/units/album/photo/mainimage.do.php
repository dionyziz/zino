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
					?>var username = document.createElement( 'span' );
					$( username ).addClass( 'name' ).append( document.createTextNode( <?php
					echo w_json_encode( $photo->User->Name );
					?> ) );
					$( 'div.usersections a:first' ).html( <?php
					ob_start();
					Element( 'user/avatar' , $photo->User , 150 , '' , '' );
					echo w_json_encode( ob_get_clean() );
					?> ).append( username );
					<?php
				}
			}
		}	
	}
?>
