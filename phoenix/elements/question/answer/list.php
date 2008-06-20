<?php
    function ElementQuestionAnswerList( User $theuser ) {
        $answerfinder = New AnswerFinder();
        $answers = $answerfinder->FindByUser( $theuser );

        ?><ul><?php
        foreach ( $answers as $answer ) {
            Element( 'question/answer/view', $answer );
        }
        ?></ul><?php
    }
?>
