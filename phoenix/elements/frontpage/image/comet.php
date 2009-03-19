<?php
    class ElementFrontpageImageComet extends Element {
        public function Render() {
            global $page;
            
            ob_start();
            ?>Comet.Subscribe( 'imagesFrontpage' , Frontpage.Image.OnImageUpload );<?php
            $page->AttachInlineScript( ob_get_clean() );
        }
    }
?>
