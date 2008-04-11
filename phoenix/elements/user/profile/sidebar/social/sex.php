<?php
	function ElementUserProfileSidebarSocialSex( $theuser ) {
		if ( $theuser->Profile->Sexualorientation != '-' ) {
			?><dt><strong>Σεξουαλικές προτιμήσεις</strong></dt>
			<dd><?php
			Element( 'user/trivial/sex' , $theuser->Profile->Sexualorientation , $theuser->Gender );
			?></dd><?php
		}
	}
?>
