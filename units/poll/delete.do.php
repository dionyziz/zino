<?php

    function UnitPollDelete( tInteger $pollid ) {
        global $user;

        $poll = new Poll( $pollid->Get() );
        
        if ( !$poll->Exists() || $poll->UserId != $user->Id() ) {
            return;
        }

        $poll->DelId = 1;
        $poll->Save();
    }

?>
