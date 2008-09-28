<?php
	function UnitQuestionUndodeletea( tInteger $id, tCoalaPointer $callback ) {
		global $user;
		global $libs;
		
		$id = $id->Get();
		
		$libs->Load( 'question' );
        $question = new Question( $id );
        
        if ( !$question->Exists() ) { // Trying to affect a question that doesn't exist
        	return;
        }
        if ( !$user->UndoDeleteAnswer( $id ) ) {
        	?>alert( 'Παρουσιάστηκε κάποιο πρόβλημα κατά την διαγραφή της ερώτησης' );<?php
        	return;
        }
        
        echo $callback;
        ?>( <?php
        echo $id;
        ?> );<?php
	}
?>
