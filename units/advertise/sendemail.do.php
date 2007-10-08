<?php
	function UnitAdvertiseSendemail( tString $text , tString $from ) {
		global $rabbit_settings;
		
		?>alert( 'from email is <?php
		echo $from->Get();
		?>' );
		alert( 'text email is <?php
		echo $text->Get();
		?>' );<?php
		$to = 'chrispappas12@gmail.com';
		$subject = $rabbit_settings[ 'applicationname' ] . ": Διαφημίσεις";
		$from = $from->Get();
		$text = $text->Get();
		$text .= "\n\n Email: " . $from;
		$headers = "From: admin@chit-chat.gr";
    	if ( mail( $to , $subject , $text, $headers ) ) {
            ?>alert( 'sent' );<?php
    	}
		else {
			?>alert( 'not sent' )<?php
		}
	}
?>