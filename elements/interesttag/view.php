<?php
	function ElementInteresttagView( $text ) {
		global $page;
		global $libs;
		global $user;
		
		$libs->Load( 'interesttag' );
		$page->SetTitle( 'Ενδιαφέροντα' );
		
		$users = InterestTag_List( $text );
		Element( 'user/profile/friends' , $users, true );
	}
?>
