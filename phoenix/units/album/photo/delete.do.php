<?php

	function UnitAlbumPhotoDelete( tInteger $photoid ) {
		global $user;
		
		$image = New Image( $photoid->Get() );
		if ( $image->User->Id == $user->Id ) {
			$albumid = $image->Album->Id;
			$image->Delete();
			if ( $albumid != 0 ) {
				?>window.location.href = '<?php
				echo $rabbit_settings[ 'webaddress' ];
				?>?p=albums&id=<?php
				echo $albumid;
				?>';<?php
			}
		}
	}
?>
