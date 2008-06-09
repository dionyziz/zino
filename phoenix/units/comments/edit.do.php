<?php
	function UnitCommentsEdit( tInteger $id, tString $text ) {
		global $libs;
		
		$libs->Load( 'comment' );
		
		$id = $id->Get();
		$text = $text->Get();
		
		if ( $text == '' ) {
			?>alert( "Δε μπορείς να δημοσιεύσεις κενό μήνυμα" );<?php
			return;
		}
		
		$comment = New Comment( $id );
		if ( !$comment->Exists() ) {
			?>alert( "Προσπαθείς να επεξεργαστείς το κείμενο ενός ανύπαρκτου σχολίου" );<?php
			return;
		}
		$comment->Text = $text;
		$comment->Save();
	}	
?>
