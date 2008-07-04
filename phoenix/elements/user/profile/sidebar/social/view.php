<?php
	function ElementUserProfileSidebarSocialView( $theuser ) {
		?><ul style="list-style:none; float:left; padding:3px 0;"><?php
		Element( 'user/profile/sidebar/social/sex' , $theuser );
		Element( 'user/profile/sidebar/social/smoker' , $theuser );
		//Element( 'user/profile/sidebar/social/drinker' , $theuser );
		//Element( 'user/profile/sidebar/social/education' , $theuser );
		//Element( 'user/profile/sidebar/social/religion' , $theuser );
		//Element( 'user/profile/sidebar/social/politics' , $theuser );
		?></ul><?php
	}
?>
