<?php
    return;
    
    set_include_path( '../../:./' );
    require_once 'libs/rabbit/rabbit.php';

    global $rabbit_settings;

    Rabbit_Construct( 'empty' );

    w_assert( $rabbit_settings[ 'production' ] === false );

    $reloaded = New Database( 'ccbetareloaded' );
    $reloaded->Connect( 'localhost' );

    $phoenix = New Database( 'ccbeta' );
    $phoenix->Connect( 'localhost' );
    
    $tables = array(
        "albums"    => array( 'album_id', "album_submithost" ),
        "comments"  => array( 'comment_id', "comment_userip" ),
        "friendrel" => array( 'frel_id', "frel_creatorip" ),
        "images"    => array( 'image_id', "image_userip" ),
        "pms"       => array( 'pm_id', "pm_userip" ),
        "profileq"  => array( 'profileq_id', "profileq_userip" ),
        "shoutbox"  => array( 'shout_id', "shout_userip" ),
        "users"     => array( 'user_id', "user_registerhost" )
    );
    
    $count = 0;
    foreach ( $tables as $table => $fields ) {
        $res = $reloaded->Prepare(
            "SELECT
                `" . implode( $fields ) . "`
            FROM
                `" . $table . "`"
        )->Execute();
        while ( $row = $res->FetchArray() ) {
            $query = $phoenix->Prepare(
                "UPDATE
                    `" . $table . "`
                SET
                    `" . $fields[ 1 ] . "` = :LongIp
                WHERE
                    `" . $fields[ 0 ] . "` = :Id"
            );
            $query->Bind( 'LongIp', ip2long( $row[ $fields[ 1 ] ] ) );
            $query->Bind( 'Id', $row[ $fields[ 0 ] ] );
            $query->Execute();
            ++$count;
        }
    }
    
    echo "$count IP addresses migrated.";
?>
