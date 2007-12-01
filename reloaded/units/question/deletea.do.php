<?php
    function UnitQuestionDeletea( tInteger $id, tCoalaPointer $callback, tCoalaPointer $newquest ) {
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
        
        if ( $user->Contributions() > $numanswers * 10 ) {
        	$numanswers = count( $user->GetAnsweredQuestions() );
       		$newquestion = $user->GetUnansweredQuestion();
       		if ( $newquestion !== false ) {
       			echo $newquest;
                ?>( <?php
                echo $newquestion->Id();
                ?>, <?php
                echo w_json_encode( myucfirst( $newquestion->Question() ) );
                ?>, <?php
                echo $id;
                ?> );<?php
            }
        }
        
        echo $callback;
        ?>( <?php
        echo w_json_encode( $id );
        ?> );<?php
    }
?>
