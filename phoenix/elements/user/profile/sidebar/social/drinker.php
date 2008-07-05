<?php
	function ElementUserProfileSidebarSocialDrinker( $theuser ) {
		if ( $theuser->Profile->Drinker != '-' ) {
			?><li><strong>Πίνεις;</strong>
			<?php
			Element( 'user/trivial/yesno' , $theuser->Profile->Drinker );
			?></li><?php
		}
	}
?>
