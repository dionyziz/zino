<?php
    class ElementUserProfileSidebarPlayer extends Element {

        public function Render( $theuser, $theuserid ) {
		?><span><?php echo htmlspecialchars($theuser->Profile->Song). " UID: " . $theuserid  ?></span><?php
        }
    }
?>