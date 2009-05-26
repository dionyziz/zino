<?php
    class ElementApiApi extends Element {
        public function Render( tText $url, tText $t ) {
            $xml = $t == 'xml';
            $url = $url->Get();
            $params = explode( '/', $url );
            switch ( $params[0] ) {
                case 'user':
                    Element( 'api/user', $params[1], $xml );
                    break;
            }
        }
    }
?>