<?php
	
	function ElementAlbumPhotoUpload( tInteger $albumid ) {
		global $water;
		global $user;
		global $rabbit_settings;
		global $page;
		
		$page->AttachStyleSheet( 'css/album/photo/upload.css' );
		$page->AttachScript( 'js/jquery.js' );
		$page->AttachScript( 'js/album/photo/list.js' );
		$water->Disable();
		
		$album = New Album( $albumid->Get() );
		if ( $album->User->Id == $user->Id && $user->HasPermission( PERMISSION_IMAGE_CREATE ) ) {
			?><form method="post" enctype="multipart/form-data" action="do/image/upload2" id="uploadform">
					<input type="hidden" name="albumid" value="<?php
					echo $album->Id;
					?>" />
					<div class="colorlink">
						Νέα φωτογραφία
					</div>
					<input type="file" name="uploadimage" onchange="PhotoList.UploadPhoto();" />
					<input type="submit" value="upload" style="display:none" />
				</form>
				<div id="uploadingwait">
					<img src="<?php
					echo $rabbit_settings[ 'imagesurl' ];
					?>ajax-loader.gif" alt="Παρακαλώ περιμένετε" title="Παρακαλώ περιμένετε" />
					Παρακαλώ περιμένετε				
				</div><?php	
		}
		return array( 'tiny' => true );
	}
?>
