<?php

    function UnitPollUndoDelete( tInteger $pollid, tCoalaPointer $callback ) {
        global $user;
        global $libs;

        $libs->Load( 'poll' );

        $poll = new Poll( $pollid->Get() );
        if ( !$poll->Exists() || $user->IsAnonymous() || $poll->UserId != $user->Id() ) {
            return;
        }
        $poll->DelId = 0;
        $poll->Save();
        
        ob_start();

        if ( !$poll->UserHasVoted( $user ) ) {
            Element( 'user/profile/poll/view', $poll, $user );
        }
        else {
            Element( 'user/profile/poll/results', $poll );
        }

        $html = ob_get_clean();

        echo $callback;
        ?>( <?php
        echo w_json_encode( $html );
        ?> );<?php
    }

?>
