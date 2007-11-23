<?php
	set_include_path( '../:./' );
	
	global $page;
	global $libs;
    global $user;
    
	require_once 'libs/rabbit/rabbit.php';
    Rabbit_Construct( 'HTML' );
    $req = $_GET;
    
	Rabbit_ClearPostGet();

    global $db;

    ob_start();

    $lines = file( $_GET[ "filename" ] );
    if ( !$lines ) {
        echo "cannot open file " . $_GET[ "filename" ];
        exit( 1 );
    }

    $sql = "";
    $count = 0;
    foreach ( $lines as $line ) {
        $line = trim( $line );
        if ( !ereg( '^--', $line ) ) {
            $sql .= " " . $line;
            ++$count;
        }
        if ( $count == 200 ) {
            $db->Query( $sql );
            $sql = "";
        }
    }
    $db->Query( $sql );

    ?>Import Done.<?php

    Rabbit_Destruct();
	
?>
