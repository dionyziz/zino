<?php

    function UnitPollOptionDelete( tInteger $id, tString $text ) {
        global $user;
        global $libs;

        $libs->Load( 'poll' );
    
        $option = new PollOption( $id->Get() );
        $poll   = $option->Poll;

        if ( !$poll->Exists() || $user->IsAnonymous() || $poll->UserId != $user->Id() ) {
            return;
        }

        $option->Delete();
    }

?>
