<?php
    define( 'TARGET', 'e:/work/kamibu/rabbit' );

    $files = explode( "\n", file_get_contents( 'rabbit.compile.lst' ) );
    
    foreach ( $files as $file ) {
        echo "Copying $file to " . TARGET . $file . "\n";
    }
?>
