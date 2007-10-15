<?php
	set_include_path( '../../../:./' );
    
	require 'libs/rabbit/rabbit.php';

    global $user, $xc_settings;
    
    Rabbit_Construct( 'empty' );

    if ( !$user->Exists() || $user->Rights() <= $xc_settings[ 'chat' ][ 'enabled' ] ) {
        return;
    }
    
?><html>
    <head>
        <title>Chat στο Chit-Chat</title>
    </head>
    <body>
        <applet code="Frontend" width="700" height="400">
            <param name="userid" value="<?php
            echo $user->Id();
            ?>" />
            <param name="username" value="<?php
            echo $user->Username();
            ?>" />
            <param name="authtoken" value="<?php
            echo $user->Authtoken();
            ?>" />
            <b>You must have Java Runtime Environment installed on this application</b>
        </applet>
    </body>
</html><?php

    Rabbit_Destrust();
    
?>
