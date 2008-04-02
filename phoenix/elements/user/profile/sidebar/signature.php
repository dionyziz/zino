<?php
	function ElementUserProfileSidebarSignature( $theuser ) {
		?><span class="subtitle"><?php
		htmlspecialchars( $theuser->Profile->Slogan );
		?></span><?php
	}
?>