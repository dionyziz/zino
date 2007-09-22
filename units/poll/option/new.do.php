<?php

    function UnitPollOptionNew( tInteger $pollid, tString $text, tCoalaPointer $callback ) {
        global $libs;
        global $user;

        $libs->Load( 'poll' );

        $option         = new Option();
        $option->PollId = $pollid->Get();
        $option->Text   = $text->Get();
        $option->Save();

        ob_start();
        Element( "poll/option/view", $option, $user );
        $html = ob_get_clean();

        echo $callback;
        ?>( <?php
        echo w_json_encode( $pollid );
        ?>, <?php
        echo w_json_encode( $html );
        ?> );<?php
    }

?>
