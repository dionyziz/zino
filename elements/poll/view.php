<?php

    function ElementPollView( tInteger $id ) {
        global $libs;
        global $page;

        $libs->Load( 'poll' );

        $page->AttachStylesheet( 'css/poll.css' );
        $page->AttachStylesheet( 'css/pollbox.css' ); 
        $page->AttachScript( 'js/poll.js' );

        $poll = new Poll( $id->Get() );

        ?><div class="poll"><?php

            Element( "poll/box", $poll );

        ?></div><?php
    }

?>
