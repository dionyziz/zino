<?php
	
	function ElementUserSettingsPersonalPhotosmall( $image ) {
		$size = $image->ProportionalSize( 100 , 100 );
		?><a href="" onclick="Settings.SelectAvatar( '<?php
		echo $image->Id;
		?>' );return false;"><?php
		Element( 'image' , $image , $size[ 0 ] , $size[ 1 ] , 'photosmall' , $image->Name , $image->Name , '' );
		?></a><?php
	}
?>
