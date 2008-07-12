<?php
	class ElementUserProfileSidebarSlogan extends Element {
        public function Render( $theuser ) {
            ?><span class="subtitle"><?php
            echo htmlspecialchars( $theuser->Profile->Slogan );
            ?></span><?php
        }
    }
?>
