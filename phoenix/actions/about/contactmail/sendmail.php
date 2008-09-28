<?php
	function ActionAboutContactmailSendmail( tText $from, tText $text ) {
		global $libs;
		
		$libs->Load( 'rabbit/helpers/validate' );
		
		// Hardcoded stuff
		$to = "oniz@kamibu.com";
		$subject = "Zino: Επικοινωνία";
		$header = "From: oniz@kamibu.com";

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
