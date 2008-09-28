<?php
    class ElementUserProfileSidebarSlogan extends Element {

        public function Render( $slogan ) {
            ?><span class="subtitle"><?php
            echo htmlspecialchars( $slogan );
            ?></span><?php
        }
    }
?>
