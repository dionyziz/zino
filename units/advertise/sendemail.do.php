<?php
	function UnitAdvertiseSendemail( tString $text , tString $from , tCoalaPointer $domnode ) {
		global $rabbit_settings;
		
		$to = 'abresas@gmail.com, dionyziz@gmail.com, chrispappas12@gmail.com';
		$subject = $rabbit_settings[ 'applicationname' ] . ": Διαφημίσεις";
		$from = $from->Get();
		$text = $text->Get();
		$text .= "\n\nEmail: " . $from;
		$headers = "From: admin@chit-chat.gr";
		//$res = mail( $to , $subject , $text , $headers );
    	$res = true;
		if ( $res ) {
            ?>var msg = document.createElement( 'span' ).appendChild( document.createTextNode( 'Ευχαριστούμε για το ενδιαφέρον σας' ) );<?php
    	}
		else {
			?>var msg = document.createElement( 'span' ).appendChild( document.createTextNode( 'Παρουσιάσθηκε σφάλμα κατά την αποστολή' ) );<?php
		}
		?>var body = document.getElementById( 'body' );
		var refnode = <?php
		echo $domnode;
		?>;
		refnode.parentNode.insertBefore( msg , refnode );
		<?php
	}
?>