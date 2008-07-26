<?php
    class ElementUserProfileSidebarSlogan extends Element {
        protected $mPersistent = array( 'slogan' );

        public function Render( $slogan ) {
            ?><span class="subtitle"><?php
            echo htmlspecialchars( $theuser->Profile->Slogan );
            ?></span><?php
        }
    }
?>
