#!/usr/bin/php
<?php
    if ( count( $argv ) != 3 ) {
        echo "/* Usage: generate.php <beta|production> <location> */\n";
        return;
    }
    $sandbox = $argv[ 1 ] == 'beta';
    $basedir = $argv[ 2 ];

    if ( !file_exists( $basedir . '/global.lst' ) ) {
        echo "/* Warning: global.lst not found in $basedir */\n";
        return;
    }

    $filelist = file( $basedir . '/global.lst', FILE_IGNORE_NEW_LINES );

    foreach ( $filelist as $file ) {
        $file = trim( $file );
        if ( !strlen( $file ) || $file[ 0 ] == '#' ) { // skip commented-out lines
            continue;
        }
        $filename = basename( $file );
        $dirname = dirname( $file );
        if ( $sandbox ) {
            // check for masked version
            if ( file_exists( $basedir . '/' . $dirname . '/_' . $filename ) ) {
                readfile( $basedir . '/' . $dirname . '/_' . $filename );
                continue;
            }
        }
        if ( file_exists( $basedir . '/' . $file ) ) {
            readfile( $basedir . '/' . $file );
            continue;
        }
        echo "/* Warning: File not found: $file */\n";
    }
?>
