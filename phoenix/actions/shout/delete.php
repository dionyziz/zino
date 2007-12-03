<?php

    function ActionShoutDelete( tInteger $id ) {
    	global $user;
    	global $libs;
    	
    	$libs->Load( 'shoutbox' );
    	
    	$id = $id->Get();

        $shout = new Shout( $id );
        $shout->Delete();

        return Redirect();
    }


?>
