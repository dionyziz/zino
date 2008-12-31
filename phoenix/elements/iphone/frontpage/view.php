<?php
    class ElementiPhoneFrontpageView extends Element {
        public function Render() {
            global $user;

            if ( !$user->Exists() ) {
                return Element( 'iphone/user/login' );
            }
            Element( 'iphone/shoutbox/list' );
        }
    }
?>
