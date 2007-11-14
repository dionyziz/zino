<?php
	function UnitInterestTagDelete( tString $text ) {
		global $libs;
		global $user;
		
		$libs->Load( 'interesttag' );
		
		$text = myescape( $text->Get() ); // JS Injection to Modal could cause SQL Injection in Database
		
		$tag = new InterestTag( $text, $user );
		if ( !$tag->Exists() ) {
			return;
		}
		$tag->Delete();
	}
?>
		
