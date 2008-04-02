<?php
	function ElementUserProfileSidebarSlogan( $theuser ) {
		?><span class="subtitle"><?php
		$profile = new Userprofile( 2 );
		//die( var_dump( $theuser->Profile->Userid ) );
		htmlspecialchars( $theuser->Profile->Slogan );
		?></span><?php
	}
?>