<?php

    function UnitPollVote( tInteger $pollid, tInteger $optionid, tCoalaPointer $callback ) {
        global $user;
        global $libs;

        $libs->Load( 'poll' );

        $vote           = new PollVote();
        $vote->PollId   = $pollid->Get();
        $vote->OptionId = $optionid->Get();
        $vote->UserId   = $user->Id();
        $vote->Save();
        
        ob_start();
        Element( 'user/profile/poll/results', new Poll( $pollid->Get() ) );
        $html = ob_get_clean();

        echo $callback;
        ?>( <?php
        echo $html;
        ?> );<?php
    }

?>
