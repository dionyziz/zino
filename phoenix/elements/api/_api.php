<?php
    class ElementApiApi extends Element {
        public function Render() {
            ob_start();
            $res = Element::MasterElement();
            $master = ob_get_clean();
            var_dump( $res );
            return $res;
        }
    }
?>