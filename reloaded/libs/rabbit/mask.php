<?php
    /* 
        Developer: Dionyziz 
    */
    
    function Rabbit_Include( $filename ) {
        global $water;
        global $rabbit_settings;
        
        // resolve into full path
        $filename = $rabbit_settings[ 'rootdir' ] . '/' . $filename;
        
        // apply masking and check for existance
        $maskres = Mask( $filename , !$rabbit_settings[ 'production' ] );
        if ( isset( $maskres[ 'error' ] ) ) {
            $water->Warning( 'Rabbit_Include failed: ' . $maskres[ 'description' ] );
            return false;
        }
        
        // include and pass the return value up the callchain
        return Rabbit_IncludeReal( $maskres[ 'realpath' ] );
    }
    
    function Rabbit_IncludeReal( /* $filename */ ) {
        // force no variables -- $filename is avoided using func_get_args()
        // include and pass the return value up the callchain
        w_assert( func_num_args() == 1 );
        return require_once func_get_arg( 0 );
    }
    
    function Mask( $filename, $allowmasked = false , $extension = '.php' ) {
        $tail = basename( $filename );
        $till = strlen( $filename ) - strlen( $tail ) - 1;
        if ( $till <= 0 ) {
            $body = '';
        }
        else {
            $body = substr( $filename, 0, $till ) . '/'; 
        }
        if ( substr( $tail , 0 , 1 ) == '_' ) {
            // unmasking cannot be forced
            return array(
                'error' => true,
                'description' => 'Unmasking cannot be forced'
            );
        }
        $fileexists = false;
        if ( $allowmasked ) {
            $maskedpath = $body . '_' . $tail . $extension;
            $fileexists = file_exists( $maskedpath );
            if ( $fileexists ) {
                return array(
                    'masked' => true,
                    'realpath' => $maskedpath
                );
            }
        }
        if ( !$fileexists ) {
            $unmaskedpath = $body . $tail . $extension;
            $fileexists = file_exists( $unmaskedpath );
            if ( $fileexists ) {
                return array(
                    'masked' => false,
                    'realpath' => $unmaskedpath
                );
            }
        }
        return array(
            'error' => true,
            'description' => 'File not found: ' . $unmaskedpath
        );
    }    
?>
