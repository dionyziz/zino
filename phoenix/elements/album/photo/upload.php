<?php
	
	function ElementAlbumPhotoUpload( tInteger $albumid ) {
		global $water;
		global $user;
		global $rabbit_settings;
		global $page;
		
		$page->AttachStyleSheet( 'css/album/photo/upload.css' );
		//$page->AttachScript( 'js/album/photo/list.js' );
		$water->Disable();
		
		$album = New Album( $albumid->Get() );
		if ( $album->User->Id == $user->Id && $user->HasPermission( PERMISSION_IMAGE_CREATE ) ) {
			?><form method="post" enctype="multipart/form-data" action="do/image/upload2" id="uploadform">
				<input type="hidden" name="albumid" value="<?php
				echo $album->Id;
				?>" />
				<a href="">
					This is a test
					<input type="file" name="uploadimage" onchange="alert( 'test' );" />
				</a><?php
				/*
				<a href="" onclick="return false;">
					<span>
					Δημιουργία φωτογραφίας
					</span>
					<input onclick="alert( 'test' );" class="uploadfile1" type="file" name="uploadimage" onchange="alert( 'done' );" />
					<!--<input class="uploadfile2" type="file" name="uploadimage" onchange="alert( 'done' );" />					
					<input class="uploadfile3" type="file" name="uploadimage" onchange="alert( 'done' );" />
					//-->
					
				</a>
				*/
				?>
				<input type="submit" value="upload" style="display:none" />
			</form><?php	
		}
		return array( 'tiny' => true );
	}
?>
