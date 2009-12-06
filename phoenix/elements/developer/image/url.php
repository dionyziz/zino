<?php
    // Content-type: text/plain
    class ElementDeveloperImageURL extends Element {
        
		public function Render( $imageid , $userid , $type = IMAGE_PROPORTIONAL_210x210 ) {
			global $xc_settings, $rabbit_settings;

            echo $xc_settings[ 'imagesurl' ] . $userid . '/';
            if ( !$rabbit_settings[ 'production' ] ) {
                echo '_';
            }
            echo $imageid . '/' . $imageid . '_' . $type . '.jpg';
        }
    }
?>
