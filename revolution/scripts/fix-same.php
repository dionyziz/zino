<?php

    global $settings;
    
    $settings = include 'settings.php';

    include '../models/db.php';

    $tables = array( 'com', 'fav', 'vote' );

    foreach ( $table as $tbl ) {
        for ( $i = 0; $i < 16; ++$i ) {
            db( "INSERT INTO `same{$tbl}s2` ( `same{$tbl}_auserid`, `same{$tbl}_buserid`, `same{$tbl}_count` )
            SELECT `same{$tbl}_auserid` , `same{$tbl}_buserid` , SUM( `same{$tbl}_count` )
            FROM `same{$tbl}s`
            WHERE `same{$tbl}_auserid` >= $i*1000 AND `same{$tbl}_auserid`< ($i+1) *1000
            GROUP BY `same{$tbl}_auserid` , `same{$tbl}_buserid`;" );
        }
    }


?>
