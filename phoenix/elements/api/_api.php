<?php
    class ElementApiApi extends Element {
        public function Render( tText $a ) {
            switch ( $a ) {
                case 'user':
                    Element( 'api/user' );
                    break;
            }
        }
    }
?>