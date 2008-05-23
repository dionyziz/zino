<?php
	
	function ElementAlbumPhotoUpload( tInteger $albumid ) {
		global $water;
		global $user;
		
		//$water->Disable();
		//$albumid = $albumid->Get();
		$album = New Album( $albumid->Get() );
		?><script type="text/javascript">alert( PhotoList );</script><?php
		if ( $album->User->Id == $user->Id && $user->HasPermission( PERMISSION_IMAGE_CREATE ) ) {
			?><form method="post" enctype="multipart/form-data" action="do/image/upload2" id="uploadform">
				<input type="hidden" name="albumid" value="<?php
				echo $album->Id;
				?>" />
				<input type="file" name="uploadimage" onchange="PhotoList.UploadPhoto();" />
				<input type="submit" value="upload" style="display:none" />
			</form><?php	
		}
		return array( 'tiny' => true );
	}
?>
