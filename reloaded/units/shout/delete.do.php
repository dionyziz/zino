<?php
	function UnitShoutDelete( tInteger $id ) {
		global $user;
		global $libs;
        
        $id = $id->Get();
        
		$libs->Load( 'shoutbox' );
        
		if ( !$user->CanModifyStories() ) {
			return;
		}
        
		if ( $user->CanModifyStories() ) {
			if ( !empty( $id ) ) {
				$shout = new Shout( $id );
				$shout->Delete();
			}
		}
	}
?>
