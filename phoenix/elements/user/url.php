<?php
    class ElementUserURL extends Element {
        protected $mPersistent = array( 'theuserid' );
		
		public function Render( $theuserid , $theusersubdomain ) {
            global $xc_settings;

            echo str_replace( '*', urlencode( $theusersubdomain ), $xc_settings[ 'usersubdomains' ] );
        }
    }
?>
