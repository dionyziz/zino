<?php

    function ElementDeveloperAbresasTestmc( tText $key ) {
        global $mc;

        $key = $key->Get();

        $value = $mc->get( $key );
        ?><br /><br /><br /><?php
        echo var_dump( $value );
    }

?>
