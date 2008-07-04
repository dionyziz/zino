<?php

    function ElementDeveloperAbresasTestmc( tText $key ) {
        global $mc;
        global $user;

        if ( !$user->HasPermission( PERMISSION_MEMCACHE_VIEW ) ) {
            return;
        }

        $key = $key->Get();

        $value = $mc->get( $key );
        ?><br /><br /><br /><?php
        echo var_dump( $value );
    }

?>
