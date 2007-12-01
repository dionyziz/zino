<?php
	function UnitQuestionAnswer( tInteger $questionid , tString $answer, tCoalaPointer $callback, tCoalaPointer $newquest ) {
		global $user;
		global $libs;
		
        $questionid = $questionid->Get();
        $answer = $answer->Get();
        $answer = trim( $answer );

        $libs->Load( 'question' );
        $question = new Question( $questionid );
        
        if ( $answer == '' || !$question->Exists() || $user->GetUnansweredQuestion() === false ) {
            return;
        }

		$user->AnswerQuestion( $questionid, $answer );
		$formatted = mformatanswers( array( myucfirst( $answer ) ) );

        echo $callback;
        ?>( <?php
        echo $questionid;
        ?>, <?php
        echo w_json_encode( $formatted[ 0 ] );
        ?>, <?php
        echo w_json_encode( $answer );
        ?> );<?php

        // Check to see if he can answer another question
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
                echo $questionid;
                ?> );<?php
            }
        }

	}
?>
