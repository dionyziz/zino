<?php

    function UnitPollOptionNew( tInteger $pollid, tText $text, tCoalaPointer $callback ) {
        global $libs;
        global $user;

        $libs->Load( 'poll' );

        $poll = New Poll( $pollid->Get() );
        if ( !$poll->Exists() || $user->IsAnonymous() || $poll->UserId != $user->Id() ) {
            return;
        }

        $option         = New PollOption();
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
