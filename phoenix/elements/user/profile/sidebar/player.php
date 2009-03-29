<?php
    class ElementUserProfileSidebarPlayer extends Element {

        public function Render( $theuser ) {
		?><div style="display:block;float:left;width:300px;height:100px;">
		<a href="<?php echo htmlspecialchars($theuser->Profile-Song); ?>">Song</a>
		</div><?php
        }
    }
?>