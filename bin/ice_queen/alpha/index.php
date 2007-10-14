<html>
    <head>
        <title>Chat on Chit-Chat</title>
    </head>
    <body>
        <applet code="Frontend" width="700" height="400">
            <param name="userid" value="<?php
            echo isset( $_GET[ 'userid' ] )? $_GET[ 'userid' ]: '';
            ?>" />
            <param name="username" value="<?php
            echo isset( $_GET[ 'username' ] ) ? $_GET[ 'username' ]: '';
            ?>" />
            <param name="authtoken" value="<?php
            echo isset( $_GET[ 'authtoken' ] ) ? $_GET[ 'authtoken' ]: '';
            ?>" />
            <b>You must have Java Runtime Environment installed on this application</b>
        </applet>
    </body>
</html>
