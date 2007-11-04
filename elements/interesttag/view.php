<?php
	function ElementInteresttagView( tString $text ) {
		global $page;
		global $libs;
		global $user;
		
		$libs->Load( 'interesttag' );
		$page->SetTitle( 'Ενδιαφέροντα' );
		$page->AttachStyleSheet( 'css/rounded.css' );
		
	//	$tag_users = InterestTag_List( $text->Get() ); // Get a list of instances of InterestTag
		$tag_users = GetUsersByInterest( $text );
		if( count( $tag_users ) == 0 ) {
			?><b>Λυπάμε, δεν υπάρχουν χρήστες με τέτοια ενδιαφέροντα</b><?php
			return;
		}
	/*	$users = array(); // A List of instances of User
		foreach ( $tag_users as $tag_user ) {
			$users[ $tag_user->UserId  ] = New User( $tag_user->UserId );
		}*/
		Element( 'user/profile/friends' , $tag_users, true );
	}
?>
