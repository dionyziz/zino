<?php
	function ActionAboutAdvertiseSendmail( tString $from, tString $text ) {
		global $libs;
		$libs->Load( 'rabbit/helpers/validate' );
		
		// Hardcoded stuff
		$to = "abresas@gmail.com, dionyziz@gmail.com, chrispappas12@gmail.com, dkaragasidis@gmail.com";
		//$to = "dkaragasidis@gmail.com";	// for testing
		$subject = "Zino: Διαφημίσεις";
		$header = "From: admin@zino.gr";

		// Get parameters
		$from = $from->Get();
		$text = $text->Get();
		
		// Check if e-mail is valid
		if ( !ValidEmail( $from ) ) {
			return Redirect( "?p=advertise&mailsent=no" );
		}
		
		// Prepare text messagea
		$text .= "\n\nEmail: " . $from;
		
		// Send message
		if ( mail( $to, $subject, $text, $header ) ) {
			return Redirect( "?p=advertise&mailsent=yes" );
		}
		else {
			return Redirect( "?p=advertise&mailsent=no" );
		}
	}
?>