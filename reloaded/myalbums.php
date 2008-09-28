<?php
    global $page;
    global $user;
    global $rabbit_settings;
    
	require_once 'libs/rabbit/rabbit.php';
    
    Rabbit_Construct();

    if ( $user->IsAnonymous() ) {
        header( 'Location: /?p=register' );
    }
    else {
        header( 'Location: /user/' . $user->Username() . '?viewingalbums=yes' );
    }

?>
