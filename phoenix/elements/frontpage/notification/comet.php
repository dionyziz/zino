<?php
    class ElementFrontpageNotificationComet extends Element {
        public function Render() {
            global $page;

            ob_start();
            ?>Comet.Subscribe( 'FrontpageNotificationNew' );<?php
            $page->AttachInlineScript( ob_get_clean() );
        }
    }
?>
