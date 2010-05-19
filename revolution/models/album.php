<?php

    class Album {
        public static function Item( $id ) {
            $res = db( 
                'SELECT
                    `album_id` AS id, `album_name` AS name, `album_delid` AS delid, `album_ownerid` AS ownerid, `album_numphotos` AS numphotos,
                    `album_ownertype` AS ownertype, `album_mainimageid` AS mainimageid
                FROM
                    `albums`
                WHERE
                    `album_id` = :id
                LIMIT 1', compact( 'id' )
            );

			return mysql_fetch_array( $res );
        }
    }

?>
