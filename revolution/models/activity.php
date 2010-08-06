<?php

    define( 'ACTIVITY_COMMENT', 1 );
    define( 'ACTIVITY_FAVOURITE', 2 );
    define( 'ACTIVITY_FRIEND', 3 );
    define( 'ACTIVITY_FAN', 4 );
    define( 'ACTIVITY_SONG', 5 );
    define( 'ACTIVITY_STATUS', 6 );
    define( 'ACTIVITY_ITEM', 7 );

    class Activity {
        public static function ListByUser( $userid, $limit = 20 ) {
			clude( "models/bulk.php" );
            clude( 'models/user.php' );
            clude( 'models/photo.php' );

            $res = db( "SELECT * FROM `activities` WHERE `activity_userid` = :userid ORDER BY `activity_created` DESC LIMIT :limit;", compact( 'userid', 'limit' ) );
            $bulkids = array();
            $rows = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                if 
                    ( $row[ 'activity_bulkid' ] != 0 ) {
                    $bulkids[ count( $rows ) ] = $row[ 'activity_bulkid' ];
                }
                $rows[] = $row;
            }
            $bulk = Bulk::FindById( $bulkids );
            $activities = array();
            foreach ( $rows as $i => $row ) {
                $activity = array();
                $activity[ 'typeid' ] = $row[ 'activity_typeid' ];
                $activity[ 'user' ] = array();
                $activity[ 'user' ][ 'id' ] = $row[ 'activity_userid' ];
                $activity[ 'created' ] = $row[ 'activity_created' ];
                switch ( $row[ 'activity_typeid' ] ) {
                    case ACTIVITY_COMMENT:
                        $activity[ 'comment' ] = array(); 
                        $activity[ 'comment' ][ 'id' ] = $row[ 'activity_refid' ];
                        $activity[ 'comment' ][ 'bulkid' ] = $row[ 'activity_bulkid' ];
                        $activity[ 'comment' ][ 'text' ] = $bulk[ $row[ 'activity_bulkid' ] ];
                        $activity[ 'item' ] = array();
                        $activity[ 'item' ][ 'id' ] = $row[ 'activity_itemid' ];
                        $activity[ 'item' ][ 'typeid' ] = $row[ 'activity_itemtype' ];
                        $activity[ 'item' ][ 'title' ] = $row[ 'activity_text' ];
                        $activity[ 'item' ][ 'url' ] = $row[ 'activity_url' ];
                        if ( $activity[ 'item' ][ 'typeid' ] == TYPE_PHOTO ) {
                            $photo = Photo::Item( $row[ 'activity_itemid' ] );
                            $activity[ 'item' ][ 'userid' ] = $photo[ 'userid' ];
                            $activity[ 'item' ][ 'username' ] = $photo[ 'username' ];
                        }
                        else if ( $activity[ 'item' ][ 'typeid' ] == TYPE_USERPROFILE ) {
                            $user = User::Item( $activity[ 'item' ][ 'id' ] );
                            $activity[ 'item' ][ 'userid' ] = $user[ 'id' ];
                            $activity[ 'item' ][ 'username' ] = $user[ 'name' ];
                        }
                        break;
                    case ACTIVITY_FAVOURITE:
                        $activity[ 'favourite' ] = array();
                        $activity[ 'favourite' ][ 'id' ] = $row[ 'activity_refid' ];
                        $activity[ 'item' ] = array();
                        $activity[ 'item' ][ 'id' ] = $row[ 'activity_itemid' ];
                        $activity[ 'item' ][ 'typeid' ] = $row[ 'activity_itemtype' ];
                        $activity[ 'item' ][ 'bulkid' ] = $row[ 'activity_bulkid' ];
                        $activity[ 'item' ][ 'title' ] = $row[ 'activity_text' ];
                        $activity[ 'item' ][ 'url' ] = $row[ 'activity_url' ];
                        if ( $row[ 'activity_bulkid' ] != 0 ) {
                            $activity[ 'item' ][ 'text' ] = $bulk[ $row[ 'activity_bulkid' ] ];
                        }
                        break;
                    case ACTIVITY_FRIEND:
                        $friend = User::Item( $row[ 'activity_itemid' ] );
                        $activity[ 'friend' ] = array();
                        $activity[ 'friend' ][ 'id' ] = $row[ 'activity_itemid' ];
                        $activity[ 'friend' ][ 'name' ] = $row[ 'activity_text' ];
                        $activity[ 'friend' ][ 'subdomain' ] = $row[ 'activity_url' ];
                        $activity[ 'friend' ][ 'gender' ] = $friend[ 'gender' ];
                        $activity[ 'relation' ] = array();
                        $activity[ 'relation' ][ 'id' ] = $row[ 'activity_refid' ];
                        break;
                    case ACTIVITY_FAN:
                        $fan = User::Item( $row[ 'activity_itemid' ] );
                        $activity[ 'fan' ] = array();
                        $activity[ 'fan' ][ 'id' ] = $row[ 'activity_itemid' ];
                        $activity[ 'fan' ][ 'name' ] = $row[ 'activity_text' ];
                        $activity[ 'fan' ][ 'subdomain' ] = $row[ 'activity_url' ];
                        $activity[ 'fan' ][ 'gender' ] = $fan[ 'gender' ];
                        $activity[ 'relation' ] = array();
                        $activity[ 'relation' ][ 'id' ] = $row[ 'activity_refid' ];
                        break;
                    case ACTIVITY_SONG:
                        $activity[ 'song' ] = array();
                        $activity[ 'song' ][ 'id' ] = $row[ 'activity_refid' ];
                        $activity[ 'song' ][ 'title' ] = $row[ 'activity_text' ];
                        break;
                    case ACTIVITY_STATUS:
                        $activity[ 'status' ] = array();
                        $activity[ 'status' ][ 'id' ] = $row[ 'activity_refid' ];
                        $activity[ 'status' ][ 'message' ] = $row[ 'activity_text' ];
                        break;
                    case ACTIVITY_ITEM:
                        $activity[ 'item' ] = array();
                        $activity[ 'item' ][ 'id' ] = $row[ 'activity_refid' ];
                        $activity[ 'item' ][ 'typeid' ] = $row[ 'activity_itemtype' ];
                        $activity[ 'item' ][ 'bulkid' ] = $row[ 'activity_bulkid' ];
                        $activity[ 'item' ][ 'title' ] = $row[ 'activity_text' ];
                        $activity[ 'item' ][ 'url' ] = $row[ 'activity_url' ];
                        if ( $row[ 'activity_bulkid' ] != 0 ) {
                            $activity[ 'item' ][ 'text' ] = $bulk[ $row[ 'activity_bulkid' ] ];
                        }
                        break;
                    default:
                        die( 'unknown activity type' );
                }
                $activities[] = $activity;
            }
            return $activities;
        }
    }

?>
