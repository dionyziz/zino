#!/usr/bin/env php

<?php

define( 'T_ML_COMMENT', T_COMMENT );

$source = file_get_contents( 'example.php' );
$tokens = token_get_all( $source );

foreach ( $tokens as $token ) {
    if ( is_string( $token ) ) {
        echo "\ntoken:\n$token\n";
    }
    else {
        $text = $token[ 1 ];
        echo "\ntoken:\n$text\n";
    }
}

?>
