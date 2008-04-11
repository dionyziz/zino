<?php
	function ElementUserProfileSidebarSocialDrinker( $theuser ) {
		if ( $theuser->Profile->Drinker != '-' ) {
			?><dt><strong>Πίνεις;</strong></dt>
			<dd><?php
			Element( 'user/trivial/yesno' , $theuser->Profile->Drinker );
			?></dd><?php
		}
	}
?>
