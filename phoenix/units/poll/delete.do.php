<?php

    function UnitPollDelete( tInteger $pollid ) {
        global $libs;
        global $user;
        global $rabbit_settings;
        
        $libs->Load( 'poll/poll' );
        $poll = New Poll( $pollid->Get() );
        if ( $poll->Userid == $user->Id || $user->HasPermission( PERMISSION_POLL_DELETE_ALL ) ) {
            $poll->Delete();
            ?>window.location.href = '<?php
            echo $rabbit_settings[ 'webaddress' ];
            ?>/polls';<?php
        }
    }
?>
