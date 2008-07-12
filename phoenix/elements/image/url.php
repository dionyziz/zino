<?php
    // Content-type: text/plain
    class ElementImageURL extends Element {
        public function Render( $image, $type = IMAGE_PROPORTIONAL_210x210 ) {
            global $xc_settings, $rabbit_settings;

            if ( !is_object( $image ) ) {
                echo $xc_settings[ 'staticimagesurl' ] . $image;
                return;
            }
            if ( $image->IsDeleted() ) {
                return;
            }
            echo $xc_settings[ 'imagesurl' ] . $image->Userid . '/';
            if ( !$rabbit_settings[ 'production' ] ) {
                echo '_';
            }
            echo $image->Id . '/' . $image->Id . '_' . $type . '.jpg';
        }
    }
?>
