<?php
    /*
        Developer: abresas, ted
    */
    class Event {
        public static function Types() {
            // New events here!
            // EVENT_MODEL(_ATTRIBUTE)_ACTION
            return array(
                4 => 'EVENT_COMMENT_CREATED',
                19 => 'EVENT_FRIENDRELATION_CREATED',
                38 => 'EVENT_IMAGETAG_CREATED',
                39 => 'EVENT_FAVOURITE_CREATED',
                40 => 'EVENT_USER_BIRTHDAY' // not connected with any class. Triggered by script
            );
        }
        public static function TypeByModel( $model ) {
            $rtypes = array_flip( self::Types() );
            if ( isset( $rtypes[ $model ] ) ) {
                return $rtypes[ $model ];
            }
            return false;
        }
        public static function ModelByType( $typeid ) {
            $types = self::Types();
            if ( isset( $types[ $typeid ] ) ) {
                return $types[ $typeid ];
            }
            return false;
        }
    }
    class Notification {
        public function Create( $fromuserid, $touserid, $eventmodel, $itemid ){
            $fromuserid = ( int )$fromuserid;
            $touserid = ( int )$touserid;
            $typeid = Event::TypeByModel( $eventmodel );
            db( "INSERT INTO `notify`
                    (`notify_fromuserid`, `notify_touserid`, `notify_created`, `notify_typeid`, `notify_itemid`)
                VALUES
                    (:fromuserid, :touserid, NOW(), :typeid, :itemid)", compact( 'fromuserid', 'touserid', 'eventid', 'typeid', 'itemid' )
            );
            $id = mysql_insert_id();
        }
        public static function ListRecent( $userid ) {
            include 'models/types.php';
            include 'models/bulk.php';

            $res = db( 'SELECT
                            `notify_fromuserid` AS userid, `notify_created` AS created, `notify_typeid` AS eventtypeid, `notify_itemid` AS itemid,
                            `user_name` AS username, `user_gender` AS gender, `user_avatarid` AS avatarid
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
                $row[ 'eventtype' ] = Event::ModelByType( $row[ 'eventtypeid' ] );
                if ( !isset( $idsbyeventtype[ $row[ 'eventtype' ] ] ) ) {
                    $idsbyeventtype[ $row[ 'eventtype' ] ] = array();
                }
                $idsbyeventtype[ $row[ 'eventtype' ] ][] = $row[ 'itemid' ];
                $notifications[] = array(
                    'created' => $row[ 'created' ],
                    'eventtypeid' => $row[ 'eventtypeid' ],
                    'eventtype' => $row[ 'eventtype' ],
                    'itemid' => $row[ 'itemid' ],
                    'user' => array(
                        'id' => $row[ 'userid' ],
                        'username' => $row[ 'username' ],
                        'avatarid' => $row[ 'avatarid' ],
                        'gender' => $row[ 'gender' ]
                    )
                );
            }
            foreach ( $idsbyeventtype as $type => $ids ) {
                switch ( $type ) {
                    case 'EVENT_COMMENT_CREATED':
                        $res = db( 'SELECT
                                        `comment_id` AS id, `comment_typeid` AS typeid, `comment_itemid` AS itemid, `comment_bulkid` AS bulkid
                                    FROM
                                        `comments`
                                    WHERE
                                        `comment_id` IN :ids', compact( 'ids' ) );
                        $commentinfo = array();
                        $bulkids = array();
                        while ( $row = mysql_fetch_array( $res ) ) {
                            $commentinfo[ $row[ 'id' ] ] = $row;
                            $bulkids[] = $row[ 'bulkid' ];
                        }
                        $bulk = Bulk::FindById( $bulkids );
                        foreach ( $commentinfo as $id => $comment ) {
                            $commentinfo[ $id ][ 'text' ] = $bulk[ $comment[ 'bulkid' ] ];
                        }
                        break;
                }
            }
            foreach ( $notifications as $i => $notification ) {
                switch ( $notification[ 'eventtype' ] ) {
                    case 'EVENT_COMMENT_CREATED':
                        $notifications[ $i ][ 'comment' ] = $commentinfo[ $notification[ 'itemid' ] ];
                        break;
                }
            }
            return $notifications;
        }
    }
?>
