<?php

    function ElementPollOptionView( $option, $poll, $theuser ) {
        if ( !$poll->UserHasVoted( $theuser ) ) {
            Element( "poll/option/vote", $option, $poll, $theuser );
        }
        else {
            Element( "poll/option/result", $option, $theuser );
        }
    }

?>
