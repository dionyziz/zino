<?php
    class ElementDeveloperDionyzizAttach extends Element {
        public function Render() {
            global $page;

            ?>This text should be inside the content div.<?php
            $page->AttachInlineScript( 'alert( "Hello" );' );
            ?> This text should too be inside the content div.<?php
        }
    }
?>
