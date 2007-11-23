<?php
    final class HTTPRedirection {
        private $mURL;
        
        public function URL() {
            return $this->mURL;
        }
        public function Redirect() {
            header( 'Location: ' . $this->mURL );
        }
        public function HTTPRedirection( $url ) {
            $this->mURL = $url;
        }
    }
    
    function Redirect( $target = '' ) {
        global $rabbit_settings;
        
        $url = $rabbit_settings[ 'webaddress' ] . '/' . $target;
        if ( !ValidURL( $url ) ) {
            $url = $rabbit_settings[ 'webaddress' ] . '/';
        }
        
        return New HTTPRedirection( $url );
    }
?>
