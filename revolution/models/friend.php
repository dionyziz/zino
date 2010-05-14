<?php
    define( 'FRIENDS_NONE', 0 );
    define( 'FRIENDS_A_HAS_B', 1 );
    define( 'FRIENDS_B_HAS_A', 2 );
    define( 'FRIENDS_BOTH', FRIENDS_A_HAS_B | FRIENDS_B_HAS_A );
    
    class Friend {
        public static function Item( $relationid ) {
            return array_shift( self::ItemMulti( array( $relationid ) ) );
        }
        public static function ItemMulti( $ids ) {
            $friendships = db_array(
                'SELECT
                    `relation_id` AS id, `relation_userid` AS userid
                    a.user_name AS a_name, a.user_gender AS a_gender, a.user_id AS a_id,
                    b.user_name AS b_name, b.user_gender AS b_gender, b.user_id AS b_id
                FROM 
                    `relations`
                    CROSS JOIN `users`
                        ON `relation_userid` = a.user_id
                    CROSS JOIN `users`
                        ON `relation_friendid` = b.user_id
                WHERE
                    `relation_id` IN :ids', compact( 'ids' ), 'id'
            );
            $ret = array();
            foreach ( $friendships as $i => $friendship ) {
                $ret[] = array(
                    'id' => $friendship[ 'id' ],
                    'user' => array(
                        'id' => $friendship[ 'a_id' ],
                        'name' => $friendship[ 'a_name' ],
                        'gender' => $friendship[ 'a_gender' ]
                    ),
                    'friend' => array(
                        'id' => $friendship[ 'b_id' ],
                        'name' => $friendship[ 'b_name' ],
                        'gender' => $friendship[ 'b_gender' ],
                    )
                );
            }
            return $ret;
        }
        public static function Create( $userid, $friendid, $typeid ) {
            clude( 'models/db.php' );
            return db( 'INSERT IGNORE INTO `relations` ( `relation_userid`, `relation_friendid`, `relation_typeid`, `relation_created` )
                VALUES ( :userid, :friendid, :typeid, NOW() )',
                compact( 'userid', 'friendid', 'typeid' ) );
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
