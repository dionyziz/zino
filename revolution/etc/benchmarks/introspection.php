#!/usr/bin/php
<?php

    function testfunc( $color, $iq, $size, $parts, $p4, $p5, $p6, $p7, $p8, $p9 ) {
        $magic = 0;
        if ( $color == 'red' ) {
            $magic = $iq * $size;
        }
        else {
            $magic = $iq / $size;
        }
        $magic += count( $parts ) + $p4 + $p5 + $p6 - $p7 - $p8 - $p9;
        return $magic;
    }  

    function introspect() {
        $arr = array( 
            'color' => 'green', 
            'iq' => 2125, 
            'size' => 42, 
            'parts' => array( 'head', 'hands', 'feet', 'body' ),
            'p4' => 12,
            'p5' => 22,
            'p6' => 27,
            'p7' => 33,
            'p8' => -12,
            'p9' => 42 );
        $func = New ReflectionFunction( 'testfunc' );
        
        $params = array();
        
        foreach ( $func->getParameters() as $i => $parameter ) {
            $paramname = $parameter->getName();
            if ( isset( $arr[ $paramname ] ) ) {
                $params[] = $arr[ $paramname ];
            }
        }

        return call_user_func_array( 'testfunc', $params );
    }

    function call() {
        $arr = array( 
            'color' => 'green', 
            'iq' => 2125, 
            'size' => 42, 
            'parts' => array( 'head', 'hands', 'feet', 'body' ),
            'p4' => 12,
            'p5' => 22,
            'p6' => 27,
            'p7' => 33,
            'p8' => -12,
            'p9' => 42 );
        return call_user_func_array( 'testfunc', $arr );
    }

    $total = array( 0, 0 );

    $runs = isset( $argv[ 1 ] ) ? (int)$argv[ 1 ] : 50;
    $loops = isset( $argv[ 2 ] ) ? (int)$argv[ 2 ] : 10000;

    echo "Starting $runs runs with $loops loops in each run.\n";

    for ( $j = 0; $j < $runs; ++$j ) {
        $t = microtime( true );
        if ( $j % 2 == 0 ) {
            for ( $i = 0; $i < $loops; ++$i ) {
                introspect();
            }
        }
        else {
            for ( $i = 0; $i < $loops; ++$i ) {
                call(); 
            }
        }
        $t = microtime( true ) - $t;
        $total[ $j % 2 ] += $t;
    }

    printf( "intro: %0.4fs\n", $total[ 0 ] / ( $runs / 2 )  );
    printf( "call: %0.4fs\n", $total[ 1 ] / ( $runs / 2 ) );

?>
