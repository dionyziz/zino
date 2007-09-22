<?php

    function UnitPollOptionNew( tInteger $pollid, tString $text, tCoalaPointer $callback ) {
        global $libs;
        global $user;

        $libs->Load( 'poll' );

        $option         = new PollOption();
        $option->PollId = $pollid->Get();
        $option->Text   = $text->Get();
        $option->Save();

        ob_start();
        Element( "poll/option/view", $option, $user );
        $html = ob_get_clean();

        $poll = $option->Poll;
        
        ob_start();
        var_dump( $poll );
        $dump = ob_get_clean();

        ?>alert( <?php
        echo w_json_encode( $dump );
        ?> );<?php

        echo $callback;
        ?>( <?php
        echo w_json_encode( $pollid->Get() );
        ?>, <?php
        echo w_json_encode( $html );
        ?> );<?php
    }

?>
