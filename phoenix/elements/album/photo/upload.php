<?php
	
	function ElementAlbumPhotoUpload( tInteger $albumid ) {
		global $water;
		global $user;
		
		$water->Disable();
		
		$album = New Album( $albumid->Get() );
		if ( $album->User->Id == $user->Id && $user->HasPermission( PERMISSION_IMAGE_CREATE ) ) {
			?><form method="post" enctype="multipart/form-data" action="do/image/upload2" id="uploadform">
				<input type="hidden" name="albumid" value="<?php
				echo $album->Id;
				?>" />
				<a href="">
					<img src="<?php
					echo $rabbit_settings[ 'imagesurl' ];
					?>add3.png" alt="Δημιουργία φωτογραφίας" title="Δημιουργία φωτογραφίας" />
					Δημιουργία φωτογραφίας
					<input type="file" name="uploadimage" style="opacity:0.01" onchange="PhotoList.UploadPhoto();" />
				</a>
				<input type="submit" value="upload" style="display:none" />
			</form><?php	
		}
		return array( 'tiny' => true );
	}
?>
