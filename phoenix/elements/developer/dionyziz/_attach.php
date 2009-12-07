<?php
    class ElementDeveloperDionyzizAttach extends Element {
        public function Render() {
            global $page;

            $page->AttachInlineScript( 'alert( "Hello" );' );
        }
    }
?>
