<?php
    header( 'Content-type: application/xhtml+xml' );
    // header( 'Content-type: text/html' );
    echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title></title>
        <script type="text/javascript" src="http://universe.beta.zino.gr/meteor.js"></script>
    </head>
    <body>
        <script type="text/javascript">
            function Go() {
                // Set this to something unique to this client
                Meteor.hostid = '409897502705';
                // Our Meteor server is on the data. subdomain
                Meteor.host = "universe." + location.hostname;
                // Call the test() function when data arrives
                Meteor.registerEventCallback( "process", test );
                // Join the demo channel and get last five events, then stream
                Meteor.joinChannel( "demo", 5 );
                Meteor.mode = 'stream';
                // Start streaming!
                Meteor.connect();
                // Handle incoming events
                function test( data ) {
                    alert( data )
                };
            }
        </script>
    </body>
</html>
