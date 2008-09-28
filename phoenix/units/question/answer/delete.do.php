<?php
    function UnitQuestionAnswerDelete( tInteger $id ) {
        global $user;
        global $libs;
        
        $libs->Load( 'question/answer' );
        
        $id = $id->Get();
        
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
?>
