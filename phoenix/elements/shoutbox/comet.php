<?php
    class ElementShoutboxComet extends Element {
        public function Render() {
            global $user, $page;
            
            ob_start();
            ?>Comet.Init(<?php
            echo $user->Id;
            ?>);
            Comet.Subscribe( 'shoutbox', Frontpage.Shoutbox.OnMessageArrival );
            <?php
            $page->AttachInlineScript( ob_get_clean() );
        }
    }
?>