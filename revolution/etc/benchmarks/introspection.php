#!/usr/bin/php
<?php

    // usage: $ time php introspection.php
    // change the function call on the for loop to change benchmark function

    function usage() {
        echo "Usage: \n";
        echo "$ time ./introspection.php type\n";
        echo "type = intro or call\n";
    }

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

    if ( $argc < 2 ) {
        echo "Error: too few arguments\n";
        usage();
    }

    if ( $argv[ 1 ] == 'intro' ) {
        for ( $i = 0; $i < 10000; ++$i ) {
                introspect();
        }
    }
    else if ( $argv[ 1 ] == 'call' ) {
        for ( $i = 0; $i < 10000; ++$i ) {
            call(); 
        }
    }
    else {
        echo "Error: unknown type\n";
        usage();
    }

?>
