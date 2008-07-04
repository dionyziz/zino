<?php

    function ActionMcdelete( tText $key ) {
        global $mc;
        $mc->delete( $key->Get() );

        return Redirect( '?p=testmc&key=' . $key->Get() );
    }

?>
