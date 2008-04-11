<?php	
	function ElementUserProfileSidebarSocialReligion( $theuser ) {
		if ( $theuser->Profile->Religion != '-' ) {
			?><dt><strong>Θρήσκευμα</strong></dt>
			<dd><?php
			Element( 'user/trivial/religion' , $theuser->Profile->Religion , $theuser->Gender );
			?></dd><?php
		}
	}
?>