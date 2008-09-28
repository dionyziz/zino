<?php
	
	class ElementUserSettingsPersonalPhotosmall extends Element {
		public function Render( $image ) {
			?><a href="" onclick="Settings.SelectAvatar( '<?php
			echo $image->Id;
			?>' );return false"><?php
			Element( 'image/view' , $image->Id , $image->User->Id , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , 'photosmall' ,  $image->Name , '' , false , 0 , 0 );
			?></a><?php
		}
	}
?>
