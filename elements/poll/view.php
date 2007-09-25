<?php

    function ElementPollView( tInteger $id ) {
        global $libs;
        global $page;

        $libs->Load( 'poll' );

        $page->AttachStylesheet( 'css/poll.css' );
        $page->AttachScript( 'js/poll.js' );

        $poll = new Poll( $id->Get() );

        Element( "poll/box", $poll );
    }

?>
