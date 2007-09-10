<?php

    function UnitPollNew( tString $question, tString $options, tCoalaPointer $callback ) {
        global $user;
        global $libs;

        $libs->Load( 'poll' );

        $poll               = new Poll();
        $poll->Question     = $question->Get();
        $poll->TextOptions  = split( "\|", $options->Get() );
        $poll->UserId       = $user->Id();
        $poll->Save();

        echo $callback;
        ?>();<?php
    }

?>
