<?php
	function UnitAlbumPhotoDelete( tInteger $photoid ) {
		global $user;
		global $rabbit_settings;
		
		$image = New Image( $photoid->Get() );
		if ( $image->User->Id == $user->Id || $user->HasPermission( PERMISSION_IMAGE_DELETE_ALL ) ) {
			$albumid = $image->Albumid;
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
