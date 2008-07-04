<?php

    function ActionMcdelete( tText $key ) {
        global $mc;
        $mc->delete( $key->Get() );
        
        global $user;

        if ( !$user->HasPermission( PERMISSION_MEMCACHE_DELETE ) ) {
            return;
        }

        return Redirect( '?p=testmc&key=' . $key->Get() );
    }

?>
