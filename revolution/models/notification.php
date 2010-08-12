<?php
    /*
        Developer: abresas, ted
    */

    define( 'EVENT_COMMENT_CREATED', 4 );
    define( 'EVENT_FRIENDRELATION_CREATED', 19 );
    define( 'EVENT_IMAGETAG_CREATED', 38 );
    define( 'EVENT_FAVOURITE_CREATED', 39 );
    define( 'EVENT_USER_BIRTHDAY', 40 );

    class Notification {
        public function Create( $fromuserid, $touserid, $typeid, $itemid ){
            if ( !in_array( $typeid, array(
                EVENT_COMMENT_CREATED, EVENT_FRIENDRELATION_CREATED,
                EVENT_IMAGETAG_CREATED, EVENT_FAVOURITE_CREATED,
                EVENT_USER_BIRTHDAY ) ) ) {
                return;
            }

            $fromuserid = ( int )$fromuserid;
            $touserid = ( int )$touserid;
            db( "INSERT INTO `notify`
                    (`notify_fromuserid`, `notify_touserid`, `notify_created`, `notify_typeid`, `notify_itemid`)
                VALUES
                    (:fromuserid, :touserid, NOW(), :typeid, :itemid)", compact( 'fromuserid', 'touserid', 'eventid', 'typeid', 'itemid' )
            );
            $id = mysql_insert_id();
        }
        public static function ListRecent( $userid ) {
            clude( 'models/types.php' );

            $res = db( 'SELECT SQL_CALC_FOUND_ROWS
                            `notify_eventid` AS id, `notify_fromuserid` AS userid, `notify_created` AS created, `notify_typeid` AS eventtypeid, `notify_itemid` AS itemid,
                            `user_name` AS name, `user_gender` AS gender, `user_avatarid` AS avatarid
                        FROM
                            `notify`
                                CROSS JOIN `users`
                            ON `notify_fromuserid` = `user_id`
                        WHERE
                            `notify_touserid` = :userid AND
                            `notify_typeid` != 38
                        ORDER BY
                            `notify_eventid` DESC
                        LIMIT 20', compact( 'userid' ) );
            $idsbyeventtype = array();
            $notifications = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                if ( !isset( $idsbyeventtype[ $row[ 'eventtypeid' ] ] ) ) {
                    $idsbyeventtype[ $row[ 'eventtypeid' ] ] = array();
                }
                $idsbyeventtype[ $row[ 'eventtypeid' ] ][] = $row[ 'itemid' ];
                $user = array(
                    'id' => $row[ 'userid' ],
                    'name' => $row[ 'name' ],
                );
                if ( $row[ 'gender' ] != '-' ) { 
                    $user[ 'gender' ] = $row[ 'gender' ];
                }
                if ( $row[ 'avatarid' ] > 0 ) {
                    $user[ 'avatarid' ] = $row[ 'avatarid' ];
                }
                $notifications[] = array(
                    'id' => $row[ 'id' ],
                    'created' => $row[ 'created' ],
                    'eventtypeid' => $row[ 'eventtypeid' ],
                    'itemid' => $row[ 'itemid' ],
                    'user' => $user
                );
            }
            $res = db( 'SELECT FOUND_ROWS() AS fr' );
            $row = mysql_fetch_array( $res );
            $count = $row[ 'fr' ]; 
            foreach ( $idsbyeventtype as $type => $ids ) {
                switch ( $type ) {
                    case EVENT_COMMENT_CREATED:
                        clude( 'models/comment.php' );
                        $commentinfo = Comment::ItemMulti( $ids );
                        break;
                    case EVENT_FAVOURITE_CREATED:
                        clude( 'models/favourite.php' );
                        $favouriteinfo = Favourite::ItemMulti( $ids );
                        break;
                    case EVENT_FRIENDRELATION_CREATED:
                        clude( 'models/friend.php' );
                        $friendinfo = Friend::ItemMulti( $ids );
						foreach ( $friendinfo as $key => $val ) { //find srtenghts , could b be optimized possibly
							$friendinfo[ $key ][ 'strength' ] = Friend::Strength( $val[ 'user' ][ 'id' ], $val[ 'friend' ][ 'id' ] );
						}
                        break;
                    case EVENT_IMAGETAG_CREATED:
                        /*
                        clude( 'models/imagetag.php' );
                        $taginfo = ImageTag::ItemMulti( $ids );
                        */
                }
            }
            foreach ( $notifications as $i => $notification ) {
                switch ( $notification[ 'eventtypeid' ] ) {
                    case EVENT_COMMENT_CREATED:
                        $notifications[ $i ][ 'comment' ] = $commentinfo[ $notification[ 'itemid' ] ];
                        break;
                    case EVENT_FAVOURITE_CREATED:
                        $notifications[ $i ][ 'favourite' ] = $favouriteinfo[ $notification[ 'itemid' ] ];
                        break;
                    case EVENT_FRIENDRELATION_CREATED:
                        if ( isset( $friendinfo[ $notification[ 'itemid' ] ] ) ) {
                            $notifications[ $i ][ 'friendship' ] = $friendinfo[ $notification[ 'itemid' ] ];
                        }
                        else {
                            unset( $notifications[ $i ] );
                        }
                        break;
                    case EVENT_IMAGETAG_CREATED:
                        $notifications[ $i ][ 'tag' ] = $taginfo[ $notification[ 'itemid' ] ];
                }
            }
            return array( $notifications, $count );
        }
        public static function Delete( $notificationid, $userid ) {
            db( 'DELETE
                 FROM
                    `notify`
                 WHERE
                    `notify_eventid` = :notificationid
                    AND `notify_touserid` = :userid
                 LIMIT 1', compact( 'notificationid', 'userid' ) );
        }
        public static function DeleteByInfo( $eventtype, $itemid, $userid ) {
            db( 'DELETE
                 FROM
                    `notify`
                 WHERE
                    `notify_typeid` = :eventtype AND
                    `notify_touserid` = :userid AND
                    `notify_itemid` = :itemid
                 LIMIT 1', compact( 'eventtype', 'userid', 'itemid' ) );
        }
    }
?>
