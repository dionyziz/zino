<?php
    define( 'COMMENT_JOURNAL', 0 );
    define( 'COMMENT_PROFILE', 1 );
    define( 'COMMENT_IMAGE',   2 );
    define( 'COMMENT_POLL',    3 );
    
    final class Thread {
        public function Thread( $type, $itemid ) {
            global $db;
            global $comments;
            
            w_assert( is_int( $itemid ) );
            w_assert( is_int( $type )   );
            
            $res = $db->Query(
                "SELECT
                    `comment_id`, `comment_parentid`
                FROM
                    `$comments`
                WHERE
                    `comment_typeid` = " . $type . "
                    AND `comment_itemid` = " . $itemid . "
                    AND `comment_delid` = 0
                LIMIT
                    10000"
            );
        }
    }
?>
