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
            $ret = array_reverse( $ret ); // chronological order
            return $ret;
        }
        public static function Create( $channelid, $userid, $text ) {
            ( string )( int )$channelid == ( string )$channelid or die( 'Channelid is not an integer' );
            ( string )( int )$userid == ( string )$userid or die( 'Userid is not an integer' );

            include 'models/bulk.php';

            $text = nl2br( htmlspecialchars( $text ) );
            $bulkid = Bulk::Store( $text );
            db( 'INSERT INTO `shoutbox` 
                ( `shout_userid, `shout_channelid`, `shout_bulkid`, `shout_created`, `shout_delid` ) 
                VALUES ( :userid, :channelid, :bulkid, NOW(), 0 )', compact( 'userid', 'channelid', 'bulkid' ) );

            $id = mysql_insert_id();

            return array(
                'id' => $id,
                'text' => $text
            );
        }
    }
    class ChatChannel {
        public static function Auth( $channelid, $userid ) {
            if ( $channelid == 0 ) {
                return true;
            }

            $res = db(
                "SELECT
                    `participant_channelid`
                FROM
                    chatparticipants
                WHERE
                    `participant_channelid` = :channelid
                    AND `participant_userid` = :userid
                LIMIT 1", compact( 'channelid', 'userid' )
            );

            return mysql_num_rows( $res ) > 0;
        }
        public static function Create( $userid1, $userid2 ) {
            // check if userid1 and userid2 are already chatting in an existing channel_id
            // but make sure that no other people are in that convo
            $res = db(
                'SELECT
                    channel_id
                FROM
                    chatchannels
                    CROSS JOIN chatparticipants AS one
                        ON channel_id = one.participant_channelid
                        AND one.participant_userid = :userid1
                    CROSS JOIN chatparticipants AS two
                        ON channel_id = two.participant_channelid
                        AND two.participant_userid = :userid2
                    LEFT JOIN chatparticipants AS others
                        ON channel_id = others.participant_channelid
                        AND NOT others.participant_userid IN ( :userid1, :userid2 )
                WHERE
                    others.participant_userid IS NULL
                LIMIT 1', compact( 'userid1', 'userid2' )
            );
            if ( $res->Results() ) {
                $row = mysql_fetch_array();
                $channelid = $row[ 'channel_id' ];

                // participant #1 who initiated the chat must be shown a chat window,
                // so activate his participation,
                // however, we don't need to activate #2 who is just a passive receiver,
                // until a message is received
                db(
                    'UPDATE 
                        chatparticipants
                    SET
                        participant_active = 1
                    WHERE
                        participant_userid = :userid1
                        AND participant_channelid = :channelid
                    LIMIT 1', compact( 'userid1', 'channelid' )
                );
            }
            else {
                // verify user exists
                $res = db(
                    'SELECT user_id FROM users WHERE user_id = :userid2 LIMIT 1',
                    compact( 'userid2' )
                );
                mysql_num_rows( $res ) or die( 'Failed to create private chat; target user does not exist' );
                db(
                    'INSERT INTO 
                        chatchannels
                    ( channel_created ) VALUES ( NOW() )'
                );
                $channelid = mysql_insert_id();
                db(
                    'INSERT INTO
                        :chatparticipants
                    ( participant_userid, participant_channelid, participant_active, participant_joined ) VALUES
                    ( :userid1, :channelid, 1, NOW() ), ( :userid2, :channelid, 0, NOW() )',
                    compact( 'userid1', 'userid2', 'channelid' )
                );
            }
            
            return $channelid;
        }
    }
?>
