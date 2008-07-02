<?php

    function ElementDeveloperAbresasTestmc() {
        global $mc;

        $value = $mc->get( 'abresas' );
        ?><br /><br /><br /><?php
        echo var_dump( $value );
    }

?>
