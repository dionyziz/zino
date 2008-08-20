<?php

    function UnitPollVote( tInteger $optionid , tInteger $pollid , tCoalaPointer $node ) {
        global $libs;
        global $user;
        
        $libs->Load( 'poll/poll' );
        
        $optionid = $optionid->Get();
        $pollid = $pollid->Get();

        $vote = New PollVote();
        $vote->Userid = $user->Id;
        $vote->Optionid = $optionid;
        $vote->Pollid = $pollid;
        if ( !$vote->Exists() ){
            $vote->Save();
            $poll = New Poll( $pollid );
            ?>$( <?php
            echo $node;
            ?> ).html( <?php
            ob_start();
            Element( 'poll/result/view' , $poll , false );
            echo w_json_encode( ob_get_clean() );
            ?> ).css( "opacity" , "0 " ).animate( { opacity : "1" } , 400 );<?php
        }
    }
?>
