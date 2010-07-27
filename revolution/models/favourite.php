<?php
    class Favourite {
		public static function ListByUser( $userid ) {
			clude( 'models/db.php' );
			clude( 'models/types.php' );
			clude( 'models/journal.php' );
			clude( 'models/photo.php' );
			clude( 'models/poll.php' );

			$res = db(
                'SELECT 
					`favourite_id` AS id, `favourite_itemid` AS itemid, `favourite_typeid` AS typeid, `favourite_created` AS created
                FROM `favourites`		                    
                WHERE `favourite_userid` = :userid
                ORDER BY id DESC;', compact( 'userid' )
            );
      
            $favourite = array();
			$journalids = array();
			$imageids = array();
			$storeitemids = array();
            $pollids = array();
            $ret = array();

            while ( $row = mysql_fetch_array( $res ) ) {
                switch ( $row[ 'typeid' ] ) {
                    case TYPE_JOURNAL:
                        $journalids[ $row[ 'id' ] ] = $row[ 'itemid' ];
                        break;
                    case TYPE_POLL: 
                        $pollids[ $row[ 'id' ] ] = $row[ 'itemid' ];
                        break;
                    case TYPE_PHOTO:
                        $imageids[ $row[ 'id' ] ] = $row[ 'itemid' ];
                        break;
                    case TYPE_STOREITEM:
                        $storeitemids[ $row[ 'id' ] ] = $row[ 'itemid' ];
                        break;
				}
                $ret[] = array(
                    'created' => $row[ 'created' ],
                    'typeid' => $row[ 'typeid' ],
                    'data' => array(
                        'id' => $row[ 'id' ]
                    )
                );
            }	

            $items = array();
			$items[ TYPE_JOURNAL ] = Journal::ListByIds( $journalids );
			$items[ TYPE_POLL ] = Poll::ListByIds( $pollids );
			$items[ TYPE_PHOTO ] = Photo::ListByIds( $imageids );
            // $items[ TYPE_STOREITEM ] = ... TODO

            foreach ( $ret as $i => $favourite ) {
                if ( $ret[ $i ][ 'typeid' ] == TYPE_STOREITEM ) {
                    continue;
                }
                $typeid = $ret[ $i ][ 'typeid' ];
                $itemid = $ret[ $i ][ 'data' ][ 'id' ];
                if ( !isset( $items[ $typeid ][ $itemid ] ) ) {
                    continue;
                }
                $ret[ $i ][ 'data' ] = $items[ $typeid ][ $itemid ];
            }

            return $ret;
            // return $favourite;
		}
        public static function Item( $id ) {
            $items = self::ItemMulti( array( $id ) );
            if ( empty( $items ) ) {
                return false;
            }
            return array_shift( $items );
        }
        public static function ItemByDetails( $itemid, $typeid, $userid ) {
            // tuple ensures uniqueness
            return array_shift( db_array(
                'SELECT
                    favourite_id AS id
                FROM
                    favourites
                WHERE
                    favourite_itemid = :itemid
                    AND favourite_typeid = :typeid
                    AND favourite_userid = :userid
                LIMIT 1', compact( 'itemid', 'typeid', 'userid' )
            ) );
        }
        public static function ItemMulti( $ids ) {
             $ret = db_array(
                'SELECT
                    favourite_id AS id,
                    favourite_userid AS userid,
                    favourite_itemid AS itemid,
                    favourite_typeid AS typeid,
                    user_name AS username,
                    user_gender AS gender,
                    user_avatarid AS avatarid,
                    user_subdomain AS subdomain,
                    user_gender AS gender,
                    DATE_FORMAT(
                        FROM_DAYS(
                            TO_DAYS( NOW() ) - TO_DAYS( `profile_dob` )
                        ),
                        "%Y"
                    ) + 0 AS age,
                    place_name AS place
                FROM
                    favourites CROSS JOIN
                        users ON favourite_userid = user_id
                        CROSS JOIN userprofiles ON user_id = profile_userid
                        LEFT JOIN places ON profile_placeid = place_id
                WHERE
                    favourite_id IN :ids', compact( 'ids' ), 'id'
             );
             foreach ( $ret as $i => $row ) {
                 $ret[ $i ][ 'user' ] = array(
                    'id' => $row[ 'userid' ],
                    'name' => $row[ 'username' ],
                    'avatarid' => $row[ 'avatarid' ],
                    'gender' => $row[ 'gender' ],
                    'subdomain' => $row[ 'subdomain' ],
                    'age' => $row[ 'age' ],
                    'place' => array(
                        'name' => $row[ 'place' ]
                    )
                );
             }
             return $ret;
        }
        public static function ListByTypeAndItem( $typeid, $itemid ) {
            return db_array(
                'SELECT
                    `user_name` AS username
                FROM
                    `favourites` CROSS JOIN `users` 
                        ON `favourite_userid` = `user_id`
                WHERE
                    `favourite_typeid`=:typeid
                    AND `favourite_itemid`=:itemid',
                compact( 'typeid', 'itemid' )
            );
        }
        public static function Create( $userid, $typeid, $itemid ) {
            clude( 'models/types.php' );

            $userid > 0 or die;
            $typeid > 0 or die;
            $itemid > 0 or die;

            switch ( $typeid ) {
                case TYPE_POLL:
                    $table = 'polls';
                    $field = 'poll';
                    break;
                case TYPE_JOURNAL:
                    $table = 'journals';
                    $field = 'journal';
                    break;
                case TYPE_PHOTO:
                    $table = 'images';
                    $field = 'image';
                    break;
            }
            $res = db(
                'SELECT
                    `' . $field . '_userid` AS userid
                FROM
                    `' . $table . '`
                WHERE 
                    `' . $field . '_id` = :itemid',
                compact( 'itemid' )
            );
            mysql_num_rows( $res ) or die; // item must exist if you want to fave it
            $row = mysql_fetch_array( $res );
            $row[ 'userid' ] != $userid or die; // can't fave your own things

            db( 'INSERT INTO `favourites` SET
                    `favourite_userid` = :userid,
                    `favourite_typeid` = :typeid,
                    `favourite_itemid` = :itemid', compact( 'userid', 'typeid', 'itemid' ) );
        }
    }
?>
