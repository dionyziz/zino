<?php
    function UnitQuestionDeletea( tInteger $id, tCoalaPointer $callback ) {
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
?>
