<?php

    function UnitPollDelete( tInteger $pollid, tCoalaPointer $callback ) {
        global $user;
        global $libs;

        $libs->Load( 'poll' );

        $poll = new Poll( $pollid->Get() );
        
        if ( !$poll->Exists() || $poll->UserId != $user->Id() || $poll->DelId > 0 ) {
            return;
        }

        $poll->DelId = 1;
        $poll->Save();

        ob_start();
        Element( 'poll/new', $user );
        $html = ob_get_clean();

        echo $callback;
        ?>( <?php
        echo w_json_encode( $html );
        ?> );<?php
    }

?>
