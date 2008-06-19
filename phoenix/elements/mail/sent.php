<?php
	function ElementMailSent( tString $mailsent ) {
		// Get Parameter
		$mailsent = $mailsent->Get();
		
		if ( $mailsent == "yes" ) {
			?><p>Το μήνυμα στάλθηκε επιτυχώς.</p><?php
		}
		else {
			?><p>Παρουσιάστικε πρόβλημα στην αποστολή του μηνύματος. Παρακαλώ, προσπαθίστε ξανά αργότερα.</p><?php
		}
	}
?>
