<?php
    class ElementStoreHome extends Element {
        public function Render() {
            global $libs;
            
            $libs->Load( 'rabbit/helpers/http' );
            
            return Redirect( 'http://store.zino.gr/product/hoody' );
        }
    }
?>
