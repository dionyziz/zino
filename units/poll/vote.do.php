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

        echo $callback;
        ?>();<?php
    }

?>
