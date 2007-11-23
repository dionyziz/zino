<?php
    function ActionPMNew( tString $text, tString $to ) {
    	global $user;
    	global $libs;
    	
    	$libs->Load( 'pm' );
    	
    	$text = $text->Get();
    	$to = $to->Get();

    	$receiver = New User( $to );
    	
    	if ( !$receiver->Exists() ) { // TODO: gracefully fail
    		die( 'Σφάλμα: Δεν υπάρχει χρήστης με αυτό το όνομα!<br /><br />Επιστροφή στα <a href="../../index.php?p=pms">προσωπικά μηνύματα</a>.' );
    	}
    	
    	$pmsent = $user->SendPM( $receiver , $text );
    	
        return Redirect( '?p=pms' );
    }
?>
