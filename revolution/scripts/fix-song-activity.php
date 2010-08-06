<?php

    global $settings;
    $settings = include 'settings.php';

    include 'models/db.php';
    include 'models/music/grooveshark.php';

    function clude( $path ) {
        static $included = array();
        if ( !isset( $included[ $path ] ) ) {
            $included[ $path ] = true;
            return include $path;
        }
        return true;
    }

    $gsapi = GSAPI::getInstance(array('APIKey' => "1100e42a014847408ff940b233a39930" ) );

    $res = db( 'SELECT `activity_refid`, `song_songid` FROM `activities` LEFT JOIN `song` ON `song_id` = `activity_refid` WHERE `activity_typeid` = 5' );
    $songs = array();
    while ( $song = mysql_fetch_array( $res ) ) {
        $songs[ $song[ 'activity_refid' ] ] = $song[ 'song_songid' ];
    }

    $names = array();
    foreach ( $songs as $song => $songid ) {
        $info = $gsapi->songAbout( $songid );
        if ( !empty( $info[ 'songName' ] ) ) {
            $name = $info[ 'songName' ];
            echo "$song -> text = $name\n";
            db( 'UPDATE `activities` SET `activity_text` = :name WHERE `activity_refid` = :song AND `activity_typeid` = 5 LIMIT 1;', compact( 'song', 'name' ) );
        }
    }

    echo "done\n";

?>
