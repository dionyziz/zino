<?php

    function UnitPollDelete( tInteger $pollid ) {
        global $libs;
        global $user;
        global $rabbit_settings;
        
        $libs->Load( 'poll/poll' );
        $poll = New Poll( $pollid->Get() );
        if ( $poll->Userid == $user->Id ) {
            $poll->Delete();
            ?>window.location.href = '<?php
            echo $rabbit_settings[ 'webaddress' ];
            ?>?p=polls&username=<?php
            echo $poll->User->Name;
            ?>';<?php
        }
    }
?>
