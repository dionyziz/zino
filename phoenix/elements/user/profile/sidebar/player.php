<?php
    class ElementUserProfileSidebarPlayer extends Element {

        public function Render( $theuser ) {
		?><span><?php echo htmlspecialchars($user->Profile-Song); ?></span><?php
        }
    }
?>