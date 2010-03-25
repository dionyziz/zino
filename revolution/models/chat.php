<?php
    class ChatMessage {
        public static function ListByChannel( $channelid, $offset, $limit ) {
            ( string )( int )$channelid == ( string )$channelid or die( 'Channelid is not an integer' );
            ( string )( int )$offset == ( string )$offset or die( 'Offset is not an integer' );
            ( string )( int )$limit == ( string )$limit or die( 'Limit is not an integer' );

            $res = db(
                "SELECT
                    `shout_id` AS id,
                    `user_name` AS username, `user_id` AS userid, `user_avatarid` AS avatarid,
                    `bulk_text` AS text
                FROM
                    `shoutbox`
                    LEFT JOIN `users`
                        ON `shout_userid` = `user_id`
                    LEFT JOIN `bulk`
                        ON `shout_bulkid` = `bulk_id`
                WHERE
                    `shout_delid` = '0'
                    AND `shout_channelid` = :channelid
                ORDER BY
                    `shout_id` DESC
                LIMIT
                    :offset, :limit;", compact( 'channelid', 'offset', 'limit' )
            );
            $ret = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $ret[] = $row;
            }
            return $ret;
        }
    }
?>
