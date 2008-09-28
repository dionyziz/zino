<?php

    function UnitPollEditquestion( tInteger $pollid, tString $question ) {
        global $user;
        global $libs;

        $libs->Load( 'poll' );
    
        $poll = new Poll( $pollid->Get() );

        if ( !$poll->Exists() || $user->IsAnonymous() || $poll->UserId != $user->Id() ) {
            return;
        }

        $poll->Question = $question->Get();
        $poll->Save();
    }

?>
