<?php
    class ElementFrontpageCommentComet extends Element {
        public function Render() {
            global $page;

            ob_start();
            ?>Comet.Subscribe( 'FrontpageCommentNew' );<?php
            $page->AttachInlineScript( ob_get_clean() );
        }
    }
?>
