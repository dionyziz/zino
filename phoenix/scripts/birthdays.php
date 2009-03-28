<?php
    set_include_path( '../:./' );
    
    global $libs;
    global $page;
    
    require '../libs/rabbit/rabbit.php';
    
    Rabbit_Construct();
    
    header( 'Content-Type: text/html; charset=utf-8' );
    
    $libs->Load( 'user/user' );
    $libs->Load( 'event' );
    
    date_default_timezone_set( 'Europe/Athens' );
    
    $finder = new UserFinder();
    $arr =  $finder->FindByBirthday( ( int )date( 'm' ), ( int )date( 'd' ), 0, 5000 );
    
    foreach ( $arr as $row ) {
        $event = New Event();
        $event->Typeid = EVENT_USER_BIRTHDAY;
        $event->Userid = $row[ 0 ];
        $event->Itemid = $row[ 1 ];
        
        echo "Informing " . $event->Itemid . " of " . $event->Userid . "'s birthday.\n";
        
        $event->Save();
    }
    
    echo count( $arr ) . " birthday notifications deployed.\n";
?>
