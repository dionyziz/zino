#!/usr/bin/php
<?php
    set_include_path( '../:./' );
    
    global $libs;
    global $page;
    
    require '../libs/rabbit/rabbit.php';
    
    Rabbit_Construct();
    
    header( 'Content-Type: text/html; charset=utf-8' );
    
    $libs->Load( 'user/user' );
    $libs->Load( 'notify' );
    
    date_default_timezone_set( 'Europe/Athens' );
    
    $finder = new UserFinder();
    $arr =  $finder->FindByBirthday( ( int )date( 'm' ), ( int )date( 'd' ), 0, 5000 );
    
    foreach ( $arr as $row ) {
        $notification = New Notification();
        $notification->Typeid = EVENT_USER_BIRTHDAY;
        $notification->Userid = $row[ 0 ];
        $notification->Itemid = $row[ 1 ];
        
        echo "Informing " . $notification->Itemid . " of " . $notification->Userid . "'s birthday.\n";
        
        $notification->Save();
    }
    
    echo count( $arr ) . " birthday notifications deployed.\n";
?>
