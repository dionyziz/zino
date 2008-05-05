<?php
	
	function ElementAlbumPhotoUpload( tInteger $albumid ) {
		$albumid = $albumid->Get();
		$album = new Album( $albumid );
		?><form method="post" enctype="multipart/form-data" action="do/image/upload2">
			<input type="hidden" name="albumid" value="<?php
			echo $albumid;
			?>" />
			<input type="file" name="uploadimage" onchange="" />
			<input type="submit" value="upload" style="display:none" />
		</form><?php	
	}
?>
