<?php

    // usage: $ time php introspection.php
    // change the function call on the for loop to change benchmark function

    function testfunc( $color, $iq, $size ) {
        $magic = 0;
        if ( $color == 'red' ) {
            $magic = $iq * $size;
        }
        else {
            $magic = $iq / $size;
        }
        return $magic;
    }  

    function introspect() {
        $arr = array( 'color' => 'green', 'iq' => 2125, 'size' => 42 );
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
        $arr = array( 'color' => 'green', $iq = 2125, $size = 42 );
        return call_user_func_array( 'testfunc', $arr );
    }

    for ( $i = 0; $i < 10000; ++$i ) {
        introspect();
        // call(); 
    }

?>
