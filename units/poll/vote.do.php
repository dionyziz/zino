<?php

    function UnitPollVote( tInteger $pollid, tInteger $optionid, tCoalaPointer $callback ) {
        global $user;
        global $libs;

        $pollid     = $pollid->Get();
        $optionid   = $optionid->Get();

        $libs->Load( 'poll' );

        $poll = new Poll( $pollid );
        if ( !$poll->Exists() || $user->IsAnonymous() || $poll->UserHasVoted( $user ) ) {
            return;
        }

        $vote           = new PollVote();
        $vote->PollId   = $pollid;
        $vote->OptionId = $optionid;
        $vote->UserId   = $user->Id();
        $vote->Save();
        
        ob_start();
        Element( 'poll/view', $poll, new User( $poll->UserId ) );
        $html = ob_get_clean();

        echo $callback;
        ?>( <?php
        echo $html;
        ?> );<?php
    }

?>
