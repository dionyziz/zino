<?php
    define( 'FRIENDS_NONE', 0 );
    define( 'FRIENDS_A_HAS_B', 1 );
    define( 'FRIENDS_B_HAS_A', 2 );
    define( 'FRIENDS_BOTH', FRIENDS_A_HAS_B | FRIENDS_B_HAS_A );
    
    class Friend {
        public static function Strength( $a, $b ) {
            $friendships = db(
                'SELECT
                    `relation_userid` AS userid, `relation_friendid` AS friendid
                FROM
                    `relations`
                WHERE
                    ( `relation_userid` = :a AND `relation_friendid` = :b )
                    OR ( `relation_userid` = :b AND `relation_friendid` = :a )',
                compact( 'a', 'b' )
            );
            $strength = FRIENDS_NONE;
            while ( $row = mysql_fetch_array( $friendships ) ) {
                if ( $row[ 'userid' ] == $a ) {
                    $strength |= FRIENDS_A_HAS_B;
                }
                if ( $row[ 'userid' ] == $b ) {
                    $strength |= FRIENDS_B_HAS_A;
                }
            }
            return $strength;
        }
        public static function ItemByUserIds( $a, $b ) {
            return array_shift( db_array(
                'SELECT
                    `relation_id` AS id
                FROM
                    `relations`
                WHERE
                    `relation_userid`=:a
                    AND `relation_friendid`=:b
                LIMIT 1', compact( 'a', 'b' )
            ) );
        }
        public static function DeleteByUserIds( $a, $b ) {
            db( 'DELETE FROM
                    `relations`
                WHERE
                    `relation_userid` = :a
                    AND `relation_friendid` = :b
                LIMIT 1', compact( 'a', 'b' ) );
        }
        public static function Item( $relationid ) {
            return array_shift( self::ItemMulti( array( $relationid ) ) );
        }
        public static function ItemMulti( $ids ) {
            $friendships = db_array(
                'SELECT
                    `relation_id` AS id, `relation_userid` AS userid,
                    a.user_name AS a_name, a.user_gender AS a_gender, a.user_id AS a_id, a.user_avatarid AS a_avatarid,
                    DATE_FORMAT(
                        FROM_DAYS(
                            TO_DAYS( NOW() ) - TO_DAYS( `profile_dob` )
                        ),
                        "%Y"
                    ) + 0 AS a_age,
                    place_name AS a_place,
                    b.user_name AS b_name, b.user_gender AS b_gender, b.user_id AS b_id, b.user_avatarid AS b_avatarid
                FROM 
                    `relations`
                    CROSS JOIN `users` AS a
                        ON `relation_userid` = a.user_id
                        CROSS JOIN `userprofiles`
                            ON a.user_id = profile_userid
                        LEFT JOIN places ON profile_placeid = place_id
                    CROSS JOIN `users` AS b
                        ON `relation_friendid` = b.user_id
                WHERE
                    `relation_id` IN :ids', compact( 'ids' ), 'id'
            );
            $ret = array();
            foreach ( $friendships as $i => $friendship ) {
                $ret[ $friendship[ 'id' ] ] = array(
                    'id' => $friendship[ 'id' ],
                    'user' => array(
                        'id' => $friendship[ 'a_id' ],
                        'name' => $friendship[ 'a_name' ],
                        'gender' => $friendship[ 'a_gender' ],
                        'avatarid' => $friendship[ 'a_avatarid' ],
                        'age' => $friendship[ 'a_age' ],
                        'place' => array(
                            'name' => $friendship[ 'a_place' ]
                        )
                    ),
                    'friend' => array(
                        'id' => $friendship[ 'b_id' ],
                        'name' => $friendship[ 'b_name' ],
                        'gender' => $friendship[ 'b_gender' ],
                        'avatarid' => $friendship[ 'b_avatarid' ]
                    )
                );
            }
            return $ret;
        }
        public static function Create( $userid, $friendid, $typeid ) {
            clude( 'models/db.php' );
            db( 'INSERT IGNORE INTO `relations` ( `relation_userid`, `relation_friendid`, `relation_typeid`, `relation_created` )
                VALUES ( :userid, :friendid, :typeid, NOW() )',
                compact( 'userid', 'friendid', 'typeid' ) );
            clude( 'models/notification.php' );
            $reverserelation = self::ItemByUserIds( $friendid, $userid );
            Notification::DeleteByInfo( Event::TypeByModel( 'EVENT_FRIENDRELATION_CREATED' ), $reverserelation[ 'id' ], $userid );
        }
        public static function Delete( $userid, $friendid ) {
            clude( 'models/db.php' );
            return db( 'DELETE FROM `relations` 
                       WHERE `relation_userid` = :userid 
                       AND `relation_friendid` = :friendid', 
                       compact( 'userid', 'friendid' ) );
        }
    }
?>