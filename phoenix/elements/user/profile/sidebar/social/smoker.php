<?php
	function ElementUserProfileSidebarSocialSmoker( $theuser ) {
		if ( $theuser->Profile->Smoker != '-' ) {
			?><li><strong style="display:block; width:170px;">Καπνίζεις;</strong>
			<?php
			Element( 'user/trivial/yesno' , $theuser->Profile->Smoker );
			?></li><?php
		}
	}
?>