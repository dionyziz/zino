<?php
	function ElementUserProfileSidebarSocialEducation( $theuser ) {
		if ( $theuser->Profile->Education != '-' ) {
			?><dt><strong>Μόρφωση</strong></dt>
			<dd><?php
			Element( 'user/trivial/education' , $theuser->Profile->Education );
			?></dd><?php
		}
	}
?>
