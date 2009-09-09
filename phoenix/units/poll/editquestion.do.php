<?php

    function UnitPollEditquestion( tInteger $pollid, tText $question ) {
        global $user;
        global $libs;

        $libs->Load( 'poll' );
    
        $poll = New Poll( $pollid->Get() );

        if ( !$poll->Exists() || $user->IsAnonymous() || $poll->UserId != $user->Id() ) {
            return;
        }

        $poll->Question = $question->Get();
        $poll->Save();
    }

?>
