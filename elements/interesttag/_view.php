<?php
	function ElementInteresttagView( tString $text ) {
		global $page;
		global $libs;
		global $user;
		
		$text = $text->Get();
		
		$libs->Load( 'interesttag' );
		$page->SetTitle( 'Ενδιαφέροντα: ' . $text );
		$page->AttachStyleSheet( 'css/rounded.css' );
		
		$tags = InterestTag_List( $text );
		if( count( $tags ) == 0 ) {
			?><b>Λυπάμε, δεν υπάρχουν χρήστες με τέτοια ενδιαφέροντα</b><?php
			return;
		}

        
        $tag_users = array();
        foreach ( $tags as $tag ) {
            $tag_users[] = $tag->User;
        }

		Element( 'user/profile/friends' , $tag_users, $user->Id(), true, $text );
	}
?>
