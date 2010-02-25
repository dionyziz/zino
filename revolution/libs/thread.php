<?php
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
