<?php

    function ActionTestmc( tString $value ) {
        global $mc;

        $mc->set( 'abresas', $value->Get() );

        return Redirect( '?p=testmc' );
    }

?>
