<?php
    function ActionMemcacheDelete( tText $key ) {
        global $mc;
        global $user;

        if ( !$user->HasPermission( PERMISSION_MEMCACHE_DELETE ) ) {
            ?>Access denied.<?php
            return;
        }

        $key = $key->Get();
        $mc->delete( $key );

        return Redirect( '?p=mc&key=' . $key );
    }
?>
