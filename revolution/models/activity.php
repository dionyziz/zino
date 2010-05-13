<?php

    define( 'ACTIVITY_COMMENT', 1 );
    define( 'ACTIVITY_FAVOURITE', 2 );
    define( 'ACTIVITY_FRIEND', 3 );
    define( 'ACTIVITY_FAN', 4 );
    define( 'ACTIVITY_SONG', 5 );
    define( 'ACTIVITY_STATUS', 6 );
    define( 'ACTIVITY_ITEM', 7 );

    class Activity {
        public static function FindByUser( $userid, $limit = 100 ) {
            $res = db( "SELECT * FROM `activities` WHERE `activity_userid` = :userid LIMIT :limit;", compact( $userid, $limit ) );
            $activities = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $activities[] = $row;
            }
            return $activities;
        }
    }

?>
