<?php
	function ElementUserProfileSidebarView( $theuser ) {
		?><div class="sidebar">
			<div class="basicinfo"><?php
				Element( 'user/profile/sidebar/who' , $theuser );
				Element( 'user/profile/sidebar/signature' , $theuser );
				Element( 'user/profile/sidebar/mood' , $theuser );
				Element( 'user/profile/sidebar/info' , $theuser );
			?></div>
			<div class="look">
				<img src="http://static.zino.gr/phoenix/body-male-slim-short.jpg" alt="" /><?php
				Element( 'user/profile/sidebar/look' , $theuser );
			?></div>
			<div class="social"><?php
				Element( 'user/profile/sidebar/social' , $theuser );
			?></div>
			<div class="interests"><?php
				Element( 'user/profile/sidebar/interests' , $theuser );
			?></div>
			<div class="contacts"><?php
				Element( 'user/profile/sidebar/contacts' , $theuser );
			?></div>
			<div class="notice"><?php
				Element( 'user/profile/sidebar/notice' , $theuser );
			?></div>
		</div>
		<?php
	
	
	
	}
?>
