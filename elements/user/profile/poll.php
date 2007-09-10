<?php

    function ElementUserProfilePoll( $theuser ) {
        global $user;
        global $libs;

        $libs->Load( 'poll' );

        if ( $user->IsAnonymous() ) {
            return;
        }

        $polls = Poll_GetByUser( $theuser );

        if ( !count( $polls ) ) {
            return Element( 'user/profile/poll/new', $theuser );
        }

        $poll = $polls[ 0 ];
        if ( !$poll->UserHasVoted( $theuser ) ) {
            Element( 'user/profile/poll/view', $polls[ 0 ], $theuser );
        }
        else {
            Element( 'user/profile/poll/results', $polls[ 0 ] );
        }
    }

?>
