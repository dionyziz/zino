<?php
	
	function UnitUserSettingsTagsDelete( tInteger $tagid ) {
		global $libs;
		global $user;
		
		$libs->Load( 'tag' );
		
		$tag = New Tag( $tagid->Get() );
		if ( $tag->User->Id == $user->Id ) { 
			$tag->Delete();
		}
	}
?>
