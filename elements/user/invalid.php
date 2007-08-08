<?php
	function ElementUserInvalid() {
		global $user;
		global $page;
		
		if( $user->IsAnonymous() ) {
			$page->Title( "Εσφαλμένος Κωδικός ή Όνομα Χρήστη" );
			?><div style="margin-top:20px; width:70%;">
			Πληκτρολόγησες έναν μη έγκυρο συνδιασμό όνομα χρήστη και κωδικού πρόσβασης. <br /><b><a href="?p=lostpassword">Ξέχασες τον κωδικό σου?</a></b>
			<br />Αν δεν έχεις λογαριασμό χρήστη, <b><a href="?p=n">μπορείς τώρα να δημιουργήσεις έναν</a></b>.</div><?php
		}
		else {
            return Redirect();
		}
	}
?>
