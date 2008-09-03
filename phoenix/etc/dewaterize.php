#!/usr/bin/env php

<?php

$functions = array( 'w_assert',
    '$water->Foo',
    '$water->Baz',
    '$water->Hey' );

function dewaterize( $directory, $extensions ) {
    // TODO
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
