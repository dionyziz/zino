<?php
    class ElementCometInit extends Element {
        public function Render() {
            global $page;

            ob_start();
            ?>Comet.Init(<?php
            echo w_json_encode( uniqid() );
            ?>);<?php
            $page->AttachInlineScript( ob_get_clean() );
        }
    }
?>
