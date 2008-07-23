<?php
    
    class ElementUserSettingsPersonalPhotosmall extends Element {
        public function Render( $image ) {
            ?><a href="" onclick="Settings.SelectAvatar( '<?php
            echo $image->Id;
            ?>' );return false;"><?php
            Element( 'image/view' , $image , IMAGE_CROPPED_100x100 , 'photosmall' , $image->Name , $image->Name , '' );
            ?></a><?php
        }
    }
?>
