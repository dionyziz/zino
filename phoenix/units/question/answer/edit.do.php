<?php
    function UnitQuestionAnswerEdit( tInteger $id, tText $answertext ) {
        global $user;
        global $libs;
        
        $id = $id->Get();
        $answertext = $answertext->Get();
        
        $libs->Load( 'question/answer' );
        
        $answer = New Answer( $id );
        
        if ( ! $answer->Exists() ) {
            ?>alert( 'Η απάντηση που προσπαθείται να επεξεργαστείται δεν υπάρχει' );
            window.location.reload();<?php
            return;
        }
        if ( trim( $answertext ) == '' ) {
            ?>alert( 'Δεν μπορείς να δημοσιεύσεις μία κενή απάντηση' );
            window.location.reload();<?php
            return;
        }
        if ( $answer->Userid != $user->Id ) {
            ?>alert( 'Η απάντηση που προσπαθείς να επεξεργαστείς δεν είναι δικιά σου' );
            window.location.reload();<?php
            return;
        }
        
        $answer->Text = $answertext;
        $answer->Save();
    }
?>
