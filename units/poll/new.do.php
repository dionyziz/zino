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

        ob_start();
        Element( 'user/profile/poll/view', $poll, $user );
        $html = ob_get_clean();

        echo $callback;
        ?>( <?php
        echo $html;
        ?> );<?php
    }

?>
