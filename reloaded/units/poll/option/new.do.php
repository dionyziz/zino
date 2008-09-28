<?php

    function UnitPollOptionNew( tInteger $pollid, tString $text, tCoalaPointer $callback ) {
        global $libs;
        global $user;

        $libs->Load( 'poll' );

        $poll = new Poll( $pollid->Get() );
        if ( !$poll->Exists() || $user->IsAnonymous() || $poll->UserId != $user->Id() ) {
            return;
        }

        $option         = new PollOption();
        $option->PollId = $pollid->Get();
        $option->Text   = $text->Get();
        $option->Save();

        ob_start();
        Element( "poll/option/view", $option, $poll, $user );
        $html = ob_get_clean();

        echo $callback;
        ?>( <?php
        echo w_json_encode( $pollid->Get() );
        ?>, <?php
        echo w_json_encode( $html );
        ?> );<?php
    }

?>
