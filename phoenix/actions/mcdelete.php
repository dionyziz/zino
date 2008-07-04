<?php

    function ActionMcdelete( tText $key ) {
        global $mc;
        $mc->delete( $key->Get() );
        
        global $user;

        if ( !$user->HasPermission( PERMISSION_DELETE_MEMCACHE ) ) {
            return;
        }


        return Redirect( '?p=testmc&key=' . $key->Get() );
    }

?>
