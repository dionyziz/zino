<?php
    class ChatMessage {
        public static function ListByChannel( $channelid, $offset, $limit ) {
            ( string )( int )$channelid == ( string )$channelid or die( 'Channelid is not an integer' );
            ( string )( int )$offset == ( string )$offset or die( 'Offset is not an integer' );
            ( string )( int )$limit == ( string )$limit or die( 'Limit is not an integer' );

            clude( 'models/bulk.php' );

            $res = db(
                "SELECT
                    `shout_id` AS id,
                    `user_name` AS username, `user_id` AS userid, `user_avatarid` AS avatarid,
                    `shout_bulkid` AS bulkid
                FROM
                    `shoutbox`
                    LEFT JOIN `users`
                        ON `shout_userid` = `user_id`
                    LEFT JOIN `bulk`
                        ON `shout_bulkid` = `bulk_id`
                WHERE
                    `shout_channelid` = :channelid
                ORDER BY
                    `shout_id` DESC
                LIMIT
                    :offset, :limit;", compact( 'channelid', 'offset', 'limit' )
            );
            $ret = array();
            $bulkids = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $ret[] = $row;
                $bulkids[] = $row[ 'bulkid' ];
            }
            $bulkdata = Bulk::FindById( $bulkids );
            foreach ( $ret as $i => $row ) {
                if ( isset( $bulkdata[ $row[ 'bulkid' ] ] ) ) {
                    $ret[ $i ][ 'text' ] = $bulkdata[ $row[ 'bulkid' ] ];
                }
                else {
                    $ret[ $i ][ 'text' ] = '(text missing)';
                }
            }
            $ret = array_reverse( $ret ); // chronological order
            return $ret;
        }
        public static function Create( $channelid, $userid, $text ) {
            ( string )( int )$channelid == ( string )$channelid or die( 'Channelid is not an integer' );
            ( string )( int )$userid == ( string )$userid or die( 'Userid is not an integer' );

            clude( 'models/bulk.php' );
            clude( 'models/wysiwyg.php' );

            $text = nl2br( htmlspecialchars( $text ) );
            $text = WYSIWYG_PostProcess( $text );

            $bulkid = Bulk::Store( $text );
            db( 'INSERT INTO `shoutbox` 
                ( `shout_userid`, `shout_channelid`, `shout_bulkid`, `shout_created`, `shout_delid` ) 
                VALUES ( :userid, :channelid, :bulkid, NOW(), 0 )', compact( 'userid', 'channelid', 'bulkid' ) );

            $id = mysql_insert_id();

            if ( $channelid != 0 ) {
                ChatChannel::UpdateLastReadMessage( $channelid, $userid, $id );
            }

            return array(
                'id' => $id,
                'text' => $text
            );
        }
    }
    class ChatChannel {
        public static function ParticipantList( $channelid ) {
            if ( $channelid == 0 ) {
                return array();
            }

            $res = db( 
                "SELECT
                    `participant_userid` AS userid, `user_authtoken` AS authtoken,
                    `user_name` AS username
                FROM
                    chatparticipants
                        CROSS JOIN users
                    ON chatparticipants.participant_userid = user_id
                WHERE
                    `participant_channelid` = :channelid",
                compact( 'channelid' )
            );

            $ret = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $ret[] = $row;
            }

            return $ret;
            
        }
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
                    one.participant_channelid AS channel_id
                FROM
                    chatparticipants AS one
                    CROSS JOIN chatparticipants AS two
                        ON one.participant_channelid = two.participant_channelid
                    LEFT JOIN chatparticipants AS three
                        ON one.participant_channelid = three.participant_channelid 
                        AND three.participant_userid != :userid1
                        AND three.participant_userid != :userid2
                WHERE
                    one.participant_userid = :userid1
                    AND two.participant_userid = :userid2
                    AND three.participant_userid IS NULL', compact( 'userid1', 'userid2' )
            );
            if ( mysql_num_rows( $res ) ) {
                $row = mysql_fetch_array( $res );
                $channelid = $row[ 'channel_id' ];
            }
            else {
                // verify user exists
                $res = db(
                    'SELECT user_id FROM users WHERE user_id = :userid2 LIMIT 1',
                    compact( 'userid2' )
                );
                mysql_num_rows( $res ) or die( 'Failed to create private chat; target user does not exist' );
                $success = db(
                    'INSERT INTO 
                        chatchannels
                    ( channel_created ) VALUES ( NOW() )'
                );
                $channelid = mysql_insert_id();
                db(
                    'INSERT IGNORE INTO
                        chatparticipants
                    ( participant_userid, participant_channelid, participant_joined ) VALUES
                    ( :userid1, :channelid, NOW() ), ( :userid2, :channelid, NOW() )',
                    compact( 'userid1', 'userid2', 'channelid' )
                );
            }
            
            return $channelid;
        }
        public static function UpdateLastReadMessage( $channelid, $userid, $messageid = false ) {
            if ( $messageid === false || !is_int( $messageid ) ) {
                $messageid = ChatChannel::LastMessage( $channelid );
            }
            if ( $channelid == 0 ) {
                return false;
            }
            $success = db( 
                "UPDATE
                    `chatparticipants`
                SET
                    `participant_lastreadshoutid` = :messageid
                WHERE
                    `participant_channelid` = :channelid AND
                    `participant_userid` = :userid
                LIMIT 1;",
                compact( 'messageid', 'channelid', 'userid' )
            );

            return $success and mysql_affected_rows() == 1;
        }
        public static function LastMessage( $channelid ) {
            $res = db( 
                "SELECT 
                    `shout_id` AS i
                    d
                FROM 
                    `shoutbox` 
                WHERE 
                    `shout_channelid` = :channelid 
                ORDER BY 
                    `shout_id` DESC 
                LIMIT 1",
                compact( 'channelid' )
            );      
            $row = mysql_fetch_array( $res );
            return (int)$row[ 'id' ];
        }
    }
?>
