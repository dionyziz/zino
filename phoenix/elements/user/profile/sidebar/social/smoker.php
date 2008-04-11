<?php
	function ElementUserProfileSidebarSocialSmoker( $theuser ) {
		if ( $theuser->Profile->Smoker != '-' ) {
			?><dt><strong>Καπνίζεις;</strong></dt>
			<dd><?php
			Element( 'user/trivial/yesno' , $theuser->Profile->Smoker );
			?></dd><?php
		}
	}
?>