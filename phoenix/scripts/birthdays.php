#!/usr/bin/php
<?php
    echo "Starting...\n";

    set_include_path( '../:./' );

    global $libs;
    global $page;

    require '../libs/rabbit/rabbit.php';

    echo "Initializing rabbit...\n";
    Rabbit_Construct();
    echo "Rabbit initialized\n";

    header( 'Content-Type: text/html; charset=utf-8' );

    echo "Loading user library...\n";
    $libs->Load( 'user/user' );
    echo "Loading notify library...\n";
    $libs->Load( 'notify' );

    echo "Setting timezone...\n";
    date_default_timezone_set( 'Europe/Athens' );

    $finder = new UserFinder();
    echo "Finding users...\n";
    $arr =  $finder->FindByBirthday( ( int )date( 'm' ), ( int )date( 'd' ), 0, 5000 );
    echo "Found " . count( $arr ) . " users to inform.\n";

    foreach ( $arr as $row ) {
        echo "Informing " . ( string )$row[ 1 ] . " of " . ( string )$row[ 0 ] . "'s birthday.\n";

        $notification = New Notification();
        echo "Setting notification type...\n";
        $notification->Typeid = EVENT_USER_BIRTHDAY;
        echo "Setting notification user...\n";
        $notification->Touserid = $row[ 0 ];
        echo "Setting notification item...\n";
        $notification->Itemid = $row[ 1 ];
        echo "Saving notification...\n";
        $notification->Save();
        echo "Notification saved\n";
    }

    echo count( $arr ) . " birthday notifications deployed.\n";
?>
