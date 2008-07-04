<?php
	function ElementUserProfileSidebarSocialSex( $theuser ) {
		if ( $theuser->Profile->Sexualorientation != '-' ) {
			?><div id="sex" style="display:block; float:left; width:170px; padding:3px 0;">
			<strong >Σεξουαλικές προτιμήσεις</strong>
			<span id="orientation" style="text-align: left;margin-left: 170px;padding: 3px 0;width: 100px;">
			<?php
			Element( 'user/trivial/sex' , $theuser->Profile->Sexualorientation , $theuser->Gender );
			?></span></div><?php
		}
	}
?>
