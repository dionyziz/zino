<?php
    function ActionAboutContactmailSendmail( tText $from, tText $text ) {
        global $libs;
		global $user;
        
        $libs->Load( 'rabbit/helpers/validate' );
		$libs->Load( 'rabbit/helpers/email' );
        
        // Get parameters
        $from = $from->Get();
        $text = $text->Get();
		
		// Hardcoded stuff
        $to = "oniz@kamibu.com";
        $subject = "Zino: Επικοινωνία - " . $from;
        $oniz = "oniz@kamibu.com";
		$toname = "oniz";
		$fromname = "";
        // Check if e-mail is valid
        if ( !ValidEmail( $from ) ) {
            return Redirect( "/?p=b&mailsent=no" );
        }
        
        // Prepare text messagea
        $text .= "\n\nEmail: " . $from;
        
        $mailsent = "";
        // Send message
        if ( Email( $toname, $to, $subject, $text, $fromname, $oniz ) ) {
            $mailsent = "yes";
        }
        else {
            $mailsent = "no";
        }
        
        return Redirect( "?p=b&mailsent=" . $mailsent );
    }
?>
