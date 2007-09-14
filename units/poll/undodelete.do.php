<?php

    function UnitPollUndoDelete( tInteger $pollid, tCoalaPointer $callback ) {
        global $user;
        global $libs;

        $libs->Load( 'poll' );

        $poll = new Poll( $pollid->Get() );
        if ( !$poll->Exists() || !$user->IsAnonymous() || $poll->UserId != $user->Id() ) {
            return;
        }
        $poll->DelId = 0;
        $poll->Save();

        echo $callback;
        ?>();<?php
    }

?>
