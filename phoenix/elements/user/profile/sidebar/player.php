<?php
    class ElementUserProfileSidebarPlayer extends Element {

        public function Render( $theuser ) {
		?><span><?php echo htmlspecialchars($theuser->Profile-Song); ?></span><?php
        }
    }
?>