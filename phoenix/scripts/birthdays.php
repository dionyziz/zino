#!/usr/bin/php
<?php
    $set = explode( "\n", shell_exec( "set" ) );
    foreach ( $set as $attribute ) {
        if ( strpos( $attribute, '=' ) !== false ) {
            list( $key, $value ) = explode( '=', $attribute );
            if ( $key == 'HOSTNAME' ) {
                if ( $value == 'europa.kamibu.com' ) {
                    $_SERVER[ 'DOCUMENT_ROOT' ] = '/var/www/zino.gr/beta';
                }
            }
        }
    }
    
    set_include_path( '../:./' );

    global $libs;
    global $page;

    require '../libs/rabbit/rabbit.php';

    Rabbit_Construct();

    header( 'Content-Type: text/html; charset=utf-8' );

    echo "Loading user library...\n";
    $libs->Load( 'user/user' );
    echo "Loading notify library...\n";
    $libs->Load( 'notify' );

    date_default_timezone_set( 'Europe/Athens' );

    $finder = new UserFinder();
    echo "Finding users...\n";
    $arr =  $finder->FindByBirthday( ( int )date( 'm' ), ( int )date( 'd' ), 0, 5000 );
    echo "Found " . count( $arr ) . " users to inform.\n";

    foreach ( $arr as $row ) {
        echo "Informing " . ( string )$row[ 1 ] . " of " . ( string )$row[ 0 ] . "'s birthday.\n";

        if ( $row[ 1 ] == 1 ) {
            $notification = New Notification();
            $notification->Typeid = EVENT_USER_BIRTHDAY;
            $notification->Fromuserid = $row[ 0 ];
            $notification->Touserid = $row[ 1 ];
            $notification->Itemid = $row[ 0 ];
            $notification->Save();
        }
    }

    echo count( $arr ) . " birthday notifications deployed.\n";
?>
