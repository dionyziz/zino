<?php

    global $settings;
    
    $settings = include 'settings.php';

    include '../models/db.php';


    if ( !isset( $_GET[ 'go' ] ) ) {
        throw New Exception( "Tell me to go!" );
    }

    ?><html><body><?php
    $tables = array( 'com', 'fav', 'vote' );

    foreach ( $table as $tbl ) {
        echo "Fixing table same$tbl{s} <br />";
        for ( $i = 0; $i < 16; ++$i ) {
            echo "Fixing " . $i * 1000 . " - " . ($i+1) * 100 . "...";
            db( "INSERT INTO `same{$tbl}s2` ( `same{$tbl}_auserid`, `same{$tbl}_buserid`, `same{$tbl}_count` )
            SELECT `same{$tbl}_auserid` , `same{$tbl}_buserid` , SUM( `same{$tbl}_count` )
            FROM `same{$tbl}s`
            WHERE `same{$tbl}_auserid` >= $i*1000 AND `same{$tbl}_auserid`< ($i+1) *1000
            GROUP BY `same{$tbl}_auserid` , `same{$tbl}_buserid`;" );
            echo "ok<br />";
        }
    }
    ?></body></html><?php

?>
