<?php

    function UnitPollStop( tInteger $pollid ) {
        global $libs;
        global $user;
        
        $libs->Load( 'poll' );

        $poll = new Poll( $pollid );

        if ( !$poll->Exists() || $poll->UserId != $user->Id() ) {
            return;
        }

        $poll->Stop();
    }

?>
