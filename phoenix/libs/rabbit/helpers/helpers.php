<?php
    global $libs;
    
    $libs->Load( 'rabbit/helpers/array' );
    $libs->Load( 'rabbit/helpers/registerglobals' );
    $libs->Load( 'rabbit/helpers/magicquotes' );
    $libs->Load( 'rabbit/helpers/validate' );
    $libs->Load( 'rabbit/helpers/agent' );
    $libs->Load( 'rabbit/helpers/date' );
    $libs->Load( 'rabbit/helpers/string' );
    $libs->Load( 'rabbit/helpers/http' );
    $libs->Load( 'rabbit/helpers/file' );
    
    function uid() {
        static $uid;
        
        if ( !isset( $uid ) ) {
            $uid = 0;
        }
        
        return ++$uid;
    }
?>
