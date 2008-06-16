<?php
	function UnitInterestTagDelete( tString $text ) {
		global $libs;
		global $user;
		
		$libs->Load( 'interesttag' );
		
		$text = $text->Get();
		
		$tag = new InterestTag( $text, $user );
		if ( !$tag->Exists() ) {
			return;
		}
		$tag->Delete();
	}
?>
