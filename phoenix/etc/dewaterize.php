#!/usr/bin/env php

<?php

    $functions = array( 'w_assert',
        '$water->Foo',
        '$water->Bar',
        '$water->Hey' );

    function optimized( $source ) {
        $new = '';
        $append = true;
        $tokens = token_get_all( $source );
        foreach ( $tokens as $token ) {
            if ( is_array( $token ) ) {
                $token = $token[ 1 ];
            }
            foreach ( $functions as $func ) {
                if ( $token == $func ) {
                    $append = false;
                    break;
                }
            }
            if ( $append ) {
                $new .= $token;
            }
            if ( $token == ';' ) {
                $append = true;
            }
        }
        return $new;
    }

    function dewaterize( $directory, $extensions ) {
        $dir = opendir( $directory );
        while ( ( $file = readdir( $dir ) ) !== false ) {
            if ( $file != 'water.php' ) {
                foreach ( $extensions as $ext ) {
                    if ( substr( $file, strlen( $file ) - strlen( $ext ) ) == $ext ) {
                        file_put_contents( $file, optimized( file_get_contents( $file ) ) );
                        break;
                    }
                }
            }
        }
        closedir( $dir );
    }

    switch ( $argc ) {
        case 1:
            $directory = '.';
            $extensions = array( 'php' );
            break;
        case 2:
            $directory = $argv[ 1 ];
            $extensions = array( 'php' );
            break;
        default:
            $directory = $argv[ 1 ];
            $extensions = array_slice( $argv, 2 );
    }
    dewaterize( $directory, $extensions );

?>
