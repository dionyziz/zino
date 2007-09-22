<?php

    function ElementPollOptionView( $option, $theuser ) {
        global $user;

        $poll = $option->Poll;

        if ( !$poll->UserHasVoted( $user ) ) {
            Element( "poll/option/vote", $option, $theuser );
        }
        else {
            Element( "poll/option/result", $option, $theuser );
        }
    }

?>
