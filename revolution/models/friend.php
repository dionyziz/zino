<?php
    define( 'FRIENDS_NONE', 0 );
    define( 'FRIENDS_A_HAS_B', 1 );
    define( 'FRIENDS_B_HAS_A', 2 );
    define( 'FRIENDS_BOTH', FRIENDS_A_HAS_B | FRIENDS_B_HAS_A );
    
    class Friend {
        public static function Create( $userid, $friendid, $typeid ) {
            include 'models/db.php';
            return db( 'INSERT IGNORE INTO `relations` ( `relation_userid`, `relation_friendid`, `relation_typeid`, `relation_created` )
                VALUES ( :userid, :friendid, :typeid, NOW() )',
                compact( 'userid', 'friendid', 'typeid' ) );
        }
        public static function Delete( $userid, $friendid ) {
            include 'models/db.php';
            return db( 'DELETE FROM `relations` 
                       WHERE `relation_userid` = :userid 
                       AND `relation_friendid` = :friendid', 
                       compact( 'userid', 'friendid' ) );
        }
    }
?>
