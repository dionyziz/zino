<?php
	function ElementUserProfileSidebarSocialEducation( $theuser ) {
		if ( $theuser->Profile->Education != '-' ) {
			?><li><strong>Μόρφωση</strong>
			<?php
			Element( 'user/trivial/education' , $theuser->Profile->Education );
			?></li><?php
		}
	}
?>
