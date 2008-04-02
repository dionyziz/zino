<?php
	function ElementUserProfileSidebarSlogan( $theuser ) {
		?><span class="subtitle"><?php
		//$profile = new Userprofile( 2 );
		htmlspecialchars( $profile->Slogan );
		//die( var_dump( $theuser->Profile->Userid ) );
		echo htmlspecialchars( $theuser->Profile->Slogan );
		?></span><?php
	}
?>