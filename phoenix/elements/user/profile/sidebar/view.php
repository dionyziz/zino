<?php
	function ElementUserProfileSidebarView( $theuser ) {
		global $rabbit_settings;
		global $libs;
		global $user;
		
		$libs->Load( 'relation/relation' );
		
		?><div class="sidebar">
			<div class="basicinfo"><?php
				Element( 'user/profile/sidebar/who' , $theuser );
				Element( 'user/profile/sidebar/slogan' , $theuser );
				Element( 'user/profile/sidebar/mood' , $theuser );
				
				if ( $user->Id != $theuser->Id ) {
					$finder = New FriendRelationFinder();
					if ( !( $finder->IsFriend( $user, $theuser ) & FRIENDS_A_HAS_B == FRIENDS_A_HAS_B ) ) {
						?><div class="addfriend"><a href="" onclick="Profile.AddFriend( '<?php
						echo $theuser->Id;
						?>' );return false;">Προσθήκη στους φίλους</a></div><?php
					}
				}
				Element( 'user/profile/sidebar/info' , $theuser );
			?></div>
			<div class="look">
				<img src="<?php
				echo $rabbit_settings[ 'imagesurl' ];
				?>body-male-slim-short.jpg" alt="" /><?php
				Element( 'user/profile/sidebar/look' , $theuser );
			?></div>
			<div class="social"><?php
				Element( 'user/profile/sidebar/social/view' , $theuser );
			?></div>
			<div class="aboutme"><?php
				Element( 'user/profile/sidebar/aboutme' , $theuser );
			?></div>
			<div class="interests"><?php
				Element( 'user/profile/sidebar/interests' , $theuser );
			?></div>
			<div class="contacts"><?php
				Element( 'user/profile/sidebar/contacts' , $theuser );
			?></div>
		</div>
		<?php
	}
?>
