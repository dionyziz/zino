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
    for ( $j = 0; $j < 40; ++$j ) {
        if ( $j % 2 == 0 ) {
            $t = microtime( true );
            for ( $i = 0; $i < 10000; ++$i ) {
                introspect();
            }
            $t = microtime( true ) - $t;
            $total[ 0 ] += $t;
        }
        else {
            $t = microtime( true );
            for ( $i = 0; $i < 10000; ++$i ) {
                call(); 
            }
            $t = microtime( true ) - $t;
            $total[ 1 ] += $t;
        }
    }

    echo "Mean time intro: " . $total[ 0 ] / 20 . "\n";
    echo "Mean time call: " . $total[ 1 ] / 20 . "\n";

?>
