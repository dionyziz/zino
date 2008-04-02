<?php
	function ElementUserProfileSidebarSlogan( $theuser ) {
		?><span class="subtitle"><?php
		die( var_dump( $theuser->Profile->Userid ) );
		htmlspecialchars( $theuser->Profile->Slogan );
		?></span><?php
	}
?>