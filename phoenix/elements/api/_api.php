<?php
    class ElementApiApi extends Element {
        public function Render( tText $a ) {
            $a = $a->Get();
            echo $a;
        }
    }
?>