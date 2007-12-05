<?php
	function UnitAdvertiseSendemail( tString $text , tString $from , tCoalaPointer $domnode ) {
		global $rabbit_settings;
		
		$to = 'abresas@gmail.com, dionyziz@gmail.com, chrispappas12@gmail.com';
		$subject = $rabbit_settings[ 'applicationname' ] . ": Διαφημίσεις";
		$from = $from->Get();
		$text = $text->Get();
		$text .= "\n\nEmail: " . $from;
		$headers = "From: admin@zino.gr";
		?>var msg = document.createElement( 'div' );<?php
		if ( mail( $to , $subject , $text , $headers ) ) {
			?>msg.appendChild( document.createTextNode( 'Ευχαριστούμε για το ενδιαφέρον σας' ) );<?php
    	}
		else {
			?>msg.appendChild( document.createTextNode( 'Παρουσιάσθηκε σφάλμα κατά την αποστολή' ) );<?php
		}
		?>var refnode = <?php
		echo $domnode;
		?>;
		refnode.parentNode.insertBefore( msg , refnode.nextSibling );<?php
	}
?>
