<?php	
	function ElementUserProfileSidebarSocialReligion( $theuser ) {
		if ( $theuser->Profile->Religion != '-' ) {
			?><li><strong>Θρήσκευμα</strong>
			<?php
			Element( 'user/trivial/religion' , $theuser->Profile->Religion , $theuser->Gender );
			?></li><?php
		}
	}
?>