<?php

	function UnitAlbumPhotoDelete( tInteger $photoid ) {
		global $user;
		
		$image = New Image( $photoid->Get() );
		if ( $image->User->Id == $user->Id ) {
			$albumid = $image->Album->Id;
			if ( $image->Album->Mainimage == $image->Id ) {
				$image->Album->Mainimage = 0;
				$image->Album->Save();
				if ( $image->Album->Id == $user->Egoalbumid ) {
					?>$( 'div.usersections a img' ).attr( {
						src : ExcaliburSettings.imagesurl + 'anonymous150.jpg'
					} );<?php
				}
			}
			$image->Delete();
			if ( $albumid > 0 ) {
				?>window.location.href = '<?php
				echo $rabbit_settings[ 'webaddress' ];
				?>?p=album&id=<?php
				echo $albumid;
				?>';<?php
			}
		}
	}
?>
