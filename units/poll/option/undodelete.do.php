<?php

    function UnitPollOptionUndodelete( tInteger $id ) {
        global $user;
        global $libs;

        $libs->Load( 'poll' );
    
        $option = new PollOption( $id->Get() );
        $poll   = $option->Poll;

        if ( !$poll->Exists() || $user->IsAnonymous() || $poll->UserId != $user->Id() ) {
            return;
        }

        $option->DelId = 0;
        $option->Save();
    }

?>
