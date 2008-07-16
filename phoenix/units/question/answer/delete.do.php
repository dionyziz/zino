<?php
	function UnitQuestionAnswerDelete( tInteger $id ) {
		global $user;
		global $libs;
		
		$libs->Load( 'question/answer' );
		
		$answer = New Answer( $id );
		
		if ( !$answer->Exists() ) {
			?>alert( "Η απάντηση που προσπαθείται να διαγράψεται δεν υπάρχει" );
			window.location.reload();<?php
			return;
		}
		if ( $user->Id !== $answer->Userid ) {
			?>alert( "Η απάντηση που προσπαθείται να διαγράψεται δεν ανήκει σε εσάς" );
			window.location.reload();<?php
			return;
		}
		$answer->Delete();
	}
/*
    function UnitQuestionAnswerDelete( tInteger $id, tCoalaPointer $callback ) {
    	global $user;
        global $libs;

        $id = $id->Get();

        $libs->Load( 'question' );
        $question = new Question( $id );
        
        if ( !$question->Exists() ) {
        	return;
        }
        if ( !$user->DeleteAnswer( $id ) ) {
        	?>alert( 'Παρουσιάστηκε κάποιο πρόβλημα κατά την διαγραφή της ερώτησης' );<?php
        	return;
        }
        
        echo $callback;
        ?>( <?php
        echo w_json_encode( $id );
        ?> );<?php
    }
*/
?>
