<?php
	function ElementUserProfileSidebarSocialSmoker( $theuser ) {
		if ( $theuser->Profile->Smoker != '-' ) {
			?><li><strong>Καπνίζεις;</strong><span style="text-align:left; padding:3px 0; width: 100px;">
			<?php
			Element( 'user/trivial/yesno' , $theuser->Profile->Smoker );
			?></span></li><?php
		}
	}
?>