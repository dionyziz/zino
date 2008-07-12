<?php
    // Content-type: text/plain
    class ElementImageURL extends Element {
        protected $mPersistent = array( 'cacheid', 'type' );

        public function Render( $cacheid, Image $image, $type = IMAGE_PROPORTIONAL_210x210 ) {
            global $xc_settings, $rabbit_settings;

            if ( !is_object( $image ) ) {
                throw New Exception( 'ImageURL must be called with  aanimge argument' );
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
