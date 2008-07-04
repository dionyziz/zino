<?php
	function ElementUserProfileSidebarSocialSex( $theuser ) {
		if ( $theuser->Profile->Sexualorientation != '-' ) {
			?><li><strong>Σεξουαλικές προτιμήσεις</strong><span style="text-align:left; margin-left:170px; padding:3px 0; width: 100px;">

			<?php
			Element( 'user/trivial/sex' , $theuser->Profile->Sexualorientation , $theuser->Gender );
			?></span></li><?php
		}
	}
?>
