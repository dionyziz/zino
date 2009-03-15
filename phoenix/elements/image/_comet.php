<?php
    class ElementImageComet extends Element {
        public function Render() {
            global $user, $page;
            
            ob_start();
            ?>Comet.Init(<?php
            echo $user->Id;
            ?>);
            Comet.Subscribe( 'frontpageimage', Frontpage.Image.OnImageArrival );
            <?php
            $page->AttachInlineScript( ob_get_clean() );
        }
    }
?>
