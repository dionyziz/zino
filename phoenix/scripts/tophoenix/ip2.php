<?php
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
        "albums"    => 'album_id', "album_submithost",
        "comments"  => 'comment_id', "comment_userip",
        "friendrel" => 'frel_id', "frel_creatorip",
        "images"    => 'image_id', "image_userip",
        "ipban"     => 'ipban_id', "ipban_ip",
        "logs"      => 'log_id', "log_host",
        "places"    => 'place_id', "place_updateip",
        "pms"       => 'pm_id', "pm_userip",
        "profileq"  => 'profileq_id', "profileq_userip",
        "searches"  => "search_userip",
        "shoutbox"  => "shout_userip",
        "starring"  => "starring_userip",
        "users"     => "user_registerhost",
    );
?>
