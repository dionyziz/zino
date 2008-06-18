<?php
	function ActionAboutAdvertiseSendmail( tString $from, tString $text ) {
		global $libs;
		$libs->Load( 'rabbit/helpers/validate' );
		
		// Hardcoded stuff
		//$to = "abresas@gmail.com, dionyziz@gmail.com, chrispappas12@gmail.com, dkaragasidis@gmail.com";
		$to = "dkaragasidis@gmail.com";	// for testing
		$subject = "Zino: Διαφημίσεις";
		$header = "From: admin@zino.gr";

		// Get parameters
		$from = $from->Get();
		$text = $text->Get();
		
		// Check if e-mail is valid
		if ( !ValidEmail( $from ) ) {
			return Redirect( "/?p=advertise&mailsent=no" );
		}
		
		// Prepare text messagea
		$text .= "\n\nEmail: " . $from;
		
		$mailsent = "";
		// Send message
		if ( mail( $to, $subject, $text, $header ) ) {
			$mailsent = "yes";
		}
		else {
			$mailsent = "no";
		}
		
		//die( "Redirecting to: ?p=advertise&mailsent=" . $mailsent );
		return Redirect( "https://beta.zino.gr/phoenix/?p=advertise&mailsent=" . $mailsent );
	}
?>