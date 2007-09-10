<?php

    function UnitPollStop( tInteger $pollid ) {
        global $libs;
        
        $libs->Load( 'poll' );

        $poll = new Poll( $pollid );
        $poll->Stop();
    }

?>
