<?php
    class ElementShoutboxComet extends Element {
        public function Render() {
            global $user, $page;
            
            ob_start();
            ?>Comet.Init(<?php
            echo w_json_encode( session_id() );
            ?>);
            Comet.Subscribe( 'FrontpageShoutboxNew' );
            <?php
            $page->AttachInlineScript( ob_get_clean() );
        }
    }
?>