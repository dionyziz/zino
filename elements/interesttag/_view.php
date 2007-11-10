<?php
	function ElementInteresttagView( tString $text ) {
		global $page;
		global $libs;
		global $user;
		
		$libs->Load( 'interesttag' );
		$page->SetTitle( 'Ενδιαφέροντα' );
		$page->AttachStyleSheet( 'css/rounded.css' );
		
		$tag_users = InterestTag_List( $text->Get() );
		if( count( $tag_users ) == 0 ) {
			?><b>Λυπάμε, δεν υπάρχουν χρήστες με τέτοια ενδιαφέροντα</b><?php
			return;
		}
		Element( 'user/profile/friends' , $tag_users, $user->Id(), true );
	}
?>
