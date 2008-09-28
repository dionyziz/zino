<?php
    function UnitQuestionGet( tCoalaPointer $callback, tInteger $excludeid ) {
        global $user;
        global $libs;
        
        $excludeid = $excludeid->Get();
        
        $libs->Load( 'question/question' );
        
        $questionfinder = New QuestionFinder();
        $question = $questionfinder->FindNewQuestion( $user );
        
        if ( $question === false ) {
            return;
        }
        
        $j = 0;
        while ( $question->Id == $excludeid ) { // We don't want to have the same question returned!
            $question = $questionfinder->FindNewQuestion( $user );
            ++$j;
            if ( $j > 10 ) {
                return;
            }
        }

        echo $callback;
        ?>( <?php
        echo w_json_encode( $question->Id );
        ?> , <?php
        echo w_json_encode( $question->Text );
        ?> ); <?php
    }
?>
