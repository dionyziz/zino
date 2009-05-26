<?php
    class ElementApiTestapi extends Element {
        public function Render( tText $url ) {
            $url = $url->Get();
            $params = explode( '/', $url );
            switch ( $params[0] ) {
                case 'user':
                    Element( 'api/user', $params[1] );
                    break;
            }
        }
    }
?>