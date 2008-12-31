<?php
    class ElementiPhoneFrontpageView extends Element {
        public function Render() {
            global $user;

            if ( !$user->Exists() ) {
                return Element( 'iphone/user/login' );
            }
            ?>Hello, iPhone user!<?php
        }
    }
?>
