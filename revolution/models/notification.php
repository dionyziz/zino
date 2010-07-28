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

            $res = db( 'SELECT
                            `notify_fromuserid` AS userid, `notify_created` AS created, `notify_typeid` AS eventtypeid, `notify_itemid` AS itemid,
                            `user_name` AS name, `user_gender` AS gender, `user_avatarid` AS avatarid
                        FROM
                            `notify`
                                CROSS JOIN `users`
                            ON `notify_fromuserid` = `user_id`
                        WHERE
                            `notify_touserid` = :userid
                        ORDER BY
                            `notify_eventid` DESC', compact( 'userid' ) );
            $idsbyeventtype = array();
            $notifications = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                if ( !isset( $idsbyeventtype[ $row[ 'eventtype' ] ] ) ) {
                    $idsbyeventtype[ $row[ 'eventtype' ] ] = array();
                }
                $idsbyeventtype[ $row[ 'eventtypeid' ] ][] = $row[ 'itemid' ];
                $notifications[] = array(
                    'created' => $row[ 'created' ],
                    'eventtypeid' => $row[ 'eventtypeid' ],
                    'itemid' => $row[ 'itemid' ],
                    'user' => array(
                        'id' => $row[ 'userid' ],
                        'name' => $row[ 'name' ],
                        'avatarid' => $row[ 'avatarid' ],
                        'gender' => $row[ 'gender' ]
                    )
                );
            }
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
                    case EVENT_IMAGETAG_CREATED:
                        // todo
                }
            }
            foreach ( $notifications as $i => $notification ) {
                switch ( $notification[ 'eventtypeid' ] ) {
                    case 'EVENT_COMMENT_CREATED':
                        $notifications[ $i ][ 'comment' ] = $commentinfo[ $notification[ 'itemid' ] ];
                        break;
                    case 'EVENT_FAVOURITE_CREATED':
                        $notifications[ $i ][ 'favourite' ] = $favouriteinfo[ $notification[ 'itemid' ] ];
                        break;
                    case 'EVENT_FRIENDRELATION_CREATED':
                        $notifications[ $i ][ 'friendship' ] = $friendinfo[ $notification[ 'itemid' ] ];
                        break;
                }
            }
            return $notifications;
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
                    `notify_typeid` = :eventtype
                    AND `notify_touserid` = :userid
                    AND `notify_itemid` = :itemid
                 LIMIT 1', compact( 'eventtype', 'userid', 'itemid' ) );
        }
    }
?>
