<?php
	function ElementUserProfileSidebarSocialSex( $theuser ) {
		if ( $theuser->Profile->Sexualorientation != '-' ) {
			?><li style="clear: both; margin-bottom: 4px;"><strong style="display:block; width:170px; float:left;">Σεξουαλικές προτιμήσεις</strong>
			<?php
			Element( 'user/trivial/sex' , $theuser->Profile->Sexualorientation , $theuser->Gender );
			?></li><?php
		}
	}
?>
