<?php
	
	function ElementAlbumPhotoUpload( tInteger $albumid ) {
		global $water;
		global $user;
		global $rabbit_settings;
		global $page;
		
		$page->AttachStyleSheet( 'css/album/photo/upload.css' );
		$water->Disable();
		
		$album = New Album( $albumid->Get() );
		if ( $album->User->Id == $user->Id && $user->HasPermission( PERMISSION_IMAGE_CREATE ) ) {
			?><form method="post" enctype="multipart/form-data" action="do/image/upload2" id="uploadform">
				<input type="hidden" name="albumid" value="<?php
				echo $album->Id;
				?>" />
				<img src="<?php
				echo $rabbit_settings[ 'imagesurl' ];
				?>add3.png" alt="Δημιουργία" title="Δημιουργία" />
				<a href="" onclick="return false;">
					<span><!--<img src="<?php
					//echo $rabbit_settings[ 'imagesurl' ];
					?>add3.png" alt="Δημιουργία φωτογραφίας" title="Δημιουργία φωτογραφίας" />//-->
					Δημιουργία φωτογραφίας
					</span>
					<input onclick="return false;" class="uploadfile1" type="file" name="uploadimage" onchange="PhotoList.UploadPhoto();" />
					<input onclick="return false;" class="uploadfile2" type="file" name="uploadimage" onchange="PhotoList.UploadPhoto();" />					
					<input onclick="return false;" class="uploadfile3" type="file" name="uploadimage" onchange="PhotoList.UploadPhoto();" />
				</a>
				<input type="submit" value="upload" style="display:none" />
			</form><?php	
		}
		return array( 'tiny' => true );
	}
?>
