<?php

    function UnitPollDelete( tInteger $pollid, tCoalaPointer $callback ) {
        global $user;
        global $libs;

        $libs->Load( 'poll' );

        $poll = new Poll( $pollid->Get() );
        
        if ( !$poll->Exists() || $poll->UserId != $user->Id() ) {
            return;
        }

        $poll->DelId = 1;
        $poll->Save();

        echo $callback;
        ?>( "" );<?php
    }

?>
