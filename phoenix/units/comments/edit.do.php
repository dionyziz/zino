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
/*
    function UnitCommentsEdit( tString $text, tInteger $eid, tCoalaPointer $callback ) {
        global $libs;

        $libs->Load( 'comment' );
        
        $text = $text->Get();
        $eid = $eid->Get();

		if( $text != '' ) {
			if( $eid != 0 ) {
				$comment = New Comment ( $eid );
				$comment->Update( $text );
			}
		}

		$formatted = mformatcomments( array( $text ) );
		
		echo $callback;
		?>(<?php
		echo $eid;
		?>,<?php
		echo w_json_encode( $formatted[ 0 ] );
		?>,<?php
		echo w_json_encode( $comment->User()->Signature() );
		?>);<?php
	}
*/	
?>
