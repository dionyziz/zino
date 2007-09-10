<?php

    function ElementUserProfilePoll( $theuser ) {
        global $user;
        global $libs;

        $libs->Load( 'poll' );

        $polls = Poll_GetByUser( $theuser );

        if ( !count( $polls ) ) {
            return Element( 'user/profile/poll/new', $theuser );
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
