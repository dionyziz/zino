<?php
	function ElementUserLoginbot() {
		global $user;
		global $page;
		
		if( $user->IsAnonymous() ) {
			$page->Title( "3 Εσφαλμένοι Κωδικοί ή Ονόματα Χρήστη" );
			?><div style="margin-top:20px; width:70%;">
			Πληκτρολόγησες 3 φορές έναν μη έγκυρο συνδιασμό όνομα χρήστη και κωδικού πρόσβασης. <br /><b>Δεν θα μπορέσεις να συνδεθείς για περίπου 15min. από την πρώτη σου αποτυχημένη προσπάθεια.</b>
			<br />Αν δεν έχεις λογαριασμό χρήστη και δεν είσαι botaki, <b><a href="?p=n">μπορείς τώρα να δημιουργήσεις έναν</a></b>.</div><?php
		}
		else {
            return Redirect();
		}
	}
?>
