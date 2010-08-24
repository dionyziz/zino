<?php
    define( 'FRIENDS_NONE', 0 );
    define( 'FRIENDS_A_HAS_B', 1 );
    define( 'FRIENDS_B_HAS_A', 2 );
    define( 'FRIENDS_BOTH', FRIENDS_A_HAS_B | FRIENDS_B_HAS_A );
    
    class Friend {
        public static function ListByUser( $userid, $requiremutual = false ) {
            $sql =
                'SELECT
                    a.`relation_friendid` AS id,
                    `user_name` as name, 
                    `user_subdomain` as subdomain, 
                    `user_avatarid` as avatarid,
                    `user_gender` AS gender,
                    `profile_dob` as dob,
                    `place_id` as placeid,
                    `place_name` AS placename,
                    (
                        ( DATE_FORMAT( NOW(), "%Y" ) - DATE_FORMAT( `profile_dob`, "%Y" ))
                         - ( DATE_FORMAT( NOW(), "00-%m-%d" ) < DATE_FORMAT( `profile_dob`,"00-%m-%d" ) )
                     ) AS age,
                     ( b.`relation_id` IS NULL ) AS mutual
                FROM `relations` AS a ';
            if ( $requiremutual ) {
                $sql .= '
                    LEFT JOIN `relations` AS b
                         ON a.relation_friendid = b.relation_userid
                         AND a.relation_userid = b.relation_frienid ';
            }
            $sql .= '
                LEFT JOIN `users`
                     ON `user_id` = `relation_friendid`                  
                LEFT JOIN `userprofiles`
                    ON `profile_userid` = `relation_friendid`
                LEFT JOIN `places`
                    ON `profile_placeid` = `place_id`
                WHERE
                    a.`relation_userid` = :userid';
            if ( $requiremutual ) {
                $sql .= ' AND NOT ( b.`relation_id` IS NULL )';
            }
            $sql .= '
                ORDER BY `relation_id` DESC;';
            $res = db( $sql, compact( 'userid' ) );
      
            $friends = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                if ( $row[ 'age' ] > 100 || $row[ 'age' ] < 6 ) {
                    unset( $row[ 'age' ] );
                }
                $row[ 'id' ] = ( int )$row[ 'id' ];
                $row[ 'placeid' ] = ( int )$row[ 'placeid' ];
                $friends[] = $row;
            }	
            return $friends;
        } 
        public static function StrengthByUserAndFriends( $userid, $friendids ) {
            if ( empty( $friendids ) ) {
                return array();
            }
            $friendships = array();
            foreach ( $friendids as $id ) {
                $friendships[ $id ] = false;
            }
            $res = db(
                'SELECT
                    `relation_userid` AS userid, `relation_friendid` AS friendid
                FROM
                    `relations`
                WHERE
                    `relation_userid` = :userid AND
                    `relation_friendid` IN :friendids',
                compact( 'userid', 'friendids' )
            );
            while ( $relation = mysql_fetch_array( $res ) ) {
                $friendships[ (int)$relation[ 'friendid' ] ] = true;
            }
            return $friendships;
        }
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
            $id = mysql_insert_id();
            Notification::Create( $userid, $friendid, EVENT_FRIENDRELATION_CREATED, $id );
            $reverserelation = self::ItemByUserIds( $friendid, $userid );
            if ( isset( $reverserelation[ 'id' ] ) ) {
                Notification::DeleteByInfo( EVENT_FRIENDRELATION_CREATED, $reverserelation[ 'id' ], $userid );
            }

            return true;
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
