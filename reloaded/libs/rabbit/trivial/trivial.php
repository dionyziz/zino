<?php
    global $libs;
    
    $libs->Load( 'rabbit/trivial/array' );
    $libs->Load( 'rabbit/trivial/registerglobals' );
    $libs->Load( 'rabbit/trivial/magicquotes' );
    $libs->Load( 'rabbit/trivial/validate' );
    $libs->Load( 'rabbit/trivial/agent' );
    $libs->Load( 'rabbit/trivial/date' );
    $libs->Load( 'rabbit/trivial/string' );
    $libs->Load( 'rabbit/trivial/http' );
    
    function uid() {
        static $uid;
        
        if ( !isset( $uid ) ) {
            $uid = 0;
        }
        
        return ++$uid;
    }
?>
