<?php
    function UnitQuestionChangeq( tInteger $id, tCoalaPointer $callback ) {
        global $user;
        global $libs;

        $id = $id->Get();

        $libs->Load( 'question' );
        $question = new Question( $id );

        if ( !$question->Exists() || $user->GetUnansweredQuestion === false ) {
            return;
        }

        if ( $user->UnansweredQuestions() == 1 ) { // If changing question is not possible
            ?>alert( 'Δεν υπάρχει άλλη ερώτηση για να γίνει η αλλαγή' );<?php
            return;
        }

        $newquestion = $user->GetUnansweredQuestion();
        while ( $newquestion->Id() == $id ) { // We don't want to have the same question returned!
            $newquestion = $user->GetUnansweredQuestion();
        }

        echo $callback;
        ?>( <?php
        echo w_json_encode( $newquestion->Id() );
        ?> , <?php
        echo w_json_encode( $newquestion->Question() );
        ?> ); <?php
    }
?>
