<?php

    function ElementPollOptionView( $option, $poll, $theuser ) {
        global $user;

        if ( !$poll->UserHasVoted( $user ) ) {
            Element( "poll/option/vote", $option, $theuser );
        }
        else {
            Element( "poll/option/result", $option, $theuser );
        }
    }

?>
