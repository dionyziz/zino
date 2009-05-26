<?php
    class ElementApiTestapi extends Element {
        public function Render( tText $url ) {
            $url = $url->Get();
            echo explode( $url );
        }
    }
?>