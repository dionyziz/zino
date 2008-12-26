<?php

    function UnitPollDelete( tInteger $pollid ) {
        global $libs;
        global $user;
        global $xc_settings;
        
        $libs->Load( 'poll/poll' );
        $poll = New Poll( $pollid->Get() );
        if ( $poll->Userid == $user->Id || $user->HasPermission( PERMISSION_POLL_DELETE_ALL ) ) {
            $poll->Delete();
            ?>window.location.href = '<?php
            echo str_replace( '*', urlencode( $user->Subdomain ), $xc_settings[ 'usersubdomains' ] );
            ?>polls';<?php
        }
    }
?>
