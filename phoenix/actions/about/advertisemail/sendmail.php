<?php
	function ActionAboutAdvertisemailSendmail( tText $from, tText $text ) {
		global $libs;
        
		$libs->Load( 'rabbit/helpers/validate' );
		
		// Hardcoded stuff
		$to = "abresas@gmail.com, dionyziz@gmail.com, chrispappas12@gmail.com, dkaragasidis@gmail.com";
		$subject = "Zino: Διαφημίσεις";
		$header = "From: admin@zino.gr";

		// Get parameters
		$from = $from->Get();
		$text = $text->Get();

		// Check if e-mail is valid
		if ( !ValidEmail( $from ) ) {
			return Redirect( "/?p=b&mailsent=no" );
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
		
		return Redirect( "?p=b&mailsent=" . $mailsent );
	}
?>
