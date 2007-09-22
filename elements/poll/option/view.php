<?php

    function ElementPollOptionView( $option, $theuser ) {
        $poll = $option->Poll;

        if ( !$poll->UserHasVoted( $theuser ) ) {
            Element( "poll/option/vote", $option, $theuser );
        }
        else {
            Element( "poll/option/result", $option, $theuser );
        }
    }

?>
