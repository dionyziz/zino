<?php
    class ElementFrontpageImageComet {
        public function Render() {
            global $page;

            ob_start();
            ?>Comet.Subscribe( 'images/frontpage' , Frontpage.Image.OnImageUpload );<?php
            $page->AttachInlineScript( ob_get_clean() );

        }
    }
?>
