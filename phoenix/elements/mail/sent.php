<?php
	function ElementMailSent( tString $success ) {
		// Get Parameter
		$success = $success->Get();
		
		if ( $success == "yes" ) {
			?><p>Το μήνυμα στάλθηκε επιτυχώς.</p><?php
		}
		else {
			?><p>Παρουσιάστικε πρόβλημα στην αποστολή του μηνύματος. Παρακαλώ, προσπαθίστε ξανά αργότερα.</p><?php
		}
	}
?>
