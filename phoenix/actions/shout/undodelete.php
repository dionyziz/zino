<?php

    function ActionShoutUndoDelete( tInteger $id ) {
        global $user;
        global $libs;

        $libs->Load( 'shout' );

        $id = $id->Get();

        $shout = new Shout( $id );
        $shout->UndoDelete();

        return Redirect();
    }

?>
