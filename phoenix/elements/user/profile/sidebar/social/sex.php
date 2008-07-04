<?php
	function ElementUserProfileSidebarSocialSex( $theuser ) {
		if ( $theuser->Profile->Sexualorientation != '-' ) {
			?><li><strong style="margin-right:170px;">Σεξουαλικές προτιμήσεις</strong>
			<?php
			Element( 'user/trivial/sex' , $theuser->Profile->Sexualorientation , $theuser->Gender );
			?></li><?php
		}
	}
?>
