<?php

    function UnitPollVote( tInteger $pollid, tInteger $optionid, tCoalaPointer $callback ) {
        global $user;
        global $libs;

        $pollid     = $pollid->Get();
        $optionid   = $optionid->Get();

        $libs->Load( 'poll' );

        $poll = new Poll( $pollid );
        if ( !$poll->Exists() || $user->IsAnonymous() || $poll->UserHasVoted( $user ) ) {
            return;
        }

        $poll->Vote( $user->Id(), $optionid );
        
        ob_start();
        Element( 'poll/box', $poll, new User( $poll->UserId ) );
        $html = ob_get_clean();

        echo $callback;
        ?>( <?php
        echo $html;
        ?> );<?php
    }

?>
