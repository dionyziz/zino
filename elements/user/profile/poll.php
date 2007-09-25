<?php

    function ElementUserProfilePoll( $theuser ) {
        global $user;
        global $libs;
        global $page;
        global $water;

        $libs->Load( 'poll' );
        $page->AttachStylesheet( 'css/poll.css' );
        $page->AttachScript( 'js/poll.js' );

        if ( $user->IsAnonymous() ) {
            return;
        }

        $polls = Poll_GetByUser( $theuser, 1 );

        if ( !count( $polls ) ) {
            Element( 'poll/new', $theuser );
            
            return;
        }

        Element( 'poll/view', $polls[ 0 ], $theuser );
    }

?>
