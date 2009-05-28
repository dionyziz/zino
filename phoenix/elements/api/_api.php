<?php
    class ElementApiApi extends Element {
        public function Render( tText $a ) {
            echo $a->Get();
        }
    }
?>