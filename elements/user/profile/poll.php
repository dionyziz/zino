<?php

    function ElementUserProfilePoll( $theuser ) {
        global $user;
        global $libs;

        $libs->Load( 'poll' );

        $polls = Poll_GetByUser( $theuser );

        if ( $theuser == $user && !count( $polls ) ) {
            Element( 'user/profile/poll/new', $theuser );
        }

        if ( !count( $polls ) ) {
            return;
        }

        $poll = $polls[ 0 ];
        if ( count( $polls ) && !$poll->UserHasVoted( $theuser ) ) {
            Element( 'user/profile/poll/view', $polls[ 0 ], $theuser );
        }
        else if ( count( $polls ) ) {
            Element( 'user/profile/poll/results', $polls[ 0 ] );
        }
    }

?>
