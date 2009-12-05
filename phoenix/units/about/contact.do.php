<?php
	function UnitAboutContact( tText $reason, tText $comments, tText $abuseusername ) {
		global $user;
		global $libs;
		
		$reason = $reason->Get();
		$comments = $comments->Get();
		$abuseusername = $abuseusername->Get();
		
		$title = "Abuse Report";
		$text .= "Type of abuse: " . $reason . "\n";
		$text .= "Abuser: " . $abuseusername . "\n";
		$text .= "\n\n" . $comments . "\n";
        $text = "== $title ==\n\n" . $text;
        
		$libs->load( 'rabbit/helpers/email' );

        $subject = "[Zino-contact] " . $title;
        if ( $user->Exists() ) {
            $text = "This user is logged in: http://" . $user->Subdomain . ".zino.gr/\n\n" . $text;
            $from = $user->Subdomain . "@users.zino.gr";
        }
        else {
            $from = 'noreply@zino.gr';
        }
        $oniz = "abuse@zino.gr";
		$fromname = "";

        Email( '', 'abuse@zino.gr', $subject, $text, '', $from, 'abuse@zino.gr' );
	}
?>
