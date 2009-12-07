<?php
    class ElementDeveloperUserProfileSidebarSlogan extends Element {

        public function Render( $slogan ) {
            ?><span class="subtitle"><?php
            echo htmlspecialchars( $slogan );
            ?></span><?php
        }
    }
?>
