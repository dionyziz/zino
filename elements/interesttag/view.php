<?php
	function ElementInteresttagView( $text ) {
		global $page;
		global $libs;
		global $user;
		
		$libs->Load( 'interesttag' );
		$page->SetTitle( 'Ενδιαφέροντα' );
		
		$users = InterestTag_List( $text );
		if( count( $users ) == 0 ) {
			?><b>Λυπάμε, δεν υπάρχουν χρήστες με τέτοια ενδιαφέροντα</b><?php
			return;
		}
		Element( 'user/profile/friends' , $users, true );
		echo "y0!dude";
	}
?>
