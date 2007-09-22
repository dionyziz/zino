<?php

    function UnitPollOptionNew( tInteger $pollid, tString $text, tCoalaPointer $callback ) {
        global $libs;
        global $user;

        $libs->Load( 'poll' );

        $option         = new PollOption();
        
        ob_start();
        var_dump( $option->Poll );
        ?>alert( <?php echo w_json_encode( ob_get_clean() ) ?> );<?php

        $option->PollId = $pollid->Get();
        ob_start();
        var_dump( $option->Poll );
        ?>alert( <?php echo w_json_encode( ob_get_clean() ) ?> );<?php
        $option->Text   = $text->Get();
        $option->Save();
        ob_start();
        var_dump( $option->Poll );
        ?>alert( <?php echo w_json_encode( ob_get_clean() ) ?> );<?php

        ob_start();
        Element( "poll/option/view", $option, $user );
        $html = ob_get_clean();

        echo $callback;
        ?>( <?php
        echo w_json_encode( $pollid->Get() );
        ?>, <?php
        echo w_json_encode( $html );
        ?> );<?php
    }

?>
