<?php
	function ElementUserProfileSidebarSlogan( $theuser ) {
		?><span class="subtitle"><?php
		die( var_dump( get_class( $theuser->Profile ) ) );
		htmlspecialchars( $theuser->Profile->Slogan );
		?></span><?php
	}
?>