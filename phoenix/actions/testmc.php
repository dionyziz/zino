<?php

    function ActionTestmc( tString $value ) {
        global $mc;

        $mc->add( 'abresas', $value->Get() );

        Redirect( '?p=testmc' );
    }

?>
