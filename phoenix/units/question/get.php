<?php
    function UnitQuestionGet( tCoalaPointer $callback, tInteger $excludeid ) {
        global $user;
        global $libs;
        
        $excludeid = $excludeid->Get();
        
        $libs->Load( 'question/question' );
        
        $questionfinder = New QuestionFinder();
        $question = $questionfinder->FindNewQuestion( $user, $excludeid );
        
        if ( $question === false ) {
            return;
        }

        echo $callback;
        ?>( <?php
        echo w_json_encode( $question->Id );
        ?> , <?php
        echo w_json_encode( $question->Text );
        ?> ); <?php
    }
?>
