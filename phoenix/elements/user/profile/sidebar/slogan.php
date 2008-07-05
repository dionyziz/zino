<?php
	function ElementUserProfileSidebarSlogan( $theuser ) {
		?><span class="subtitle"><?php
		echo htmlspecialchars( $theuser->Profile->Slogan );
		?></span><?php
	}
?>