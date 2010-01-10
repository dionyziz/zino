<?php
	/* Avoid Satori/Finder base for speed */

	class ChannelFinder {
        public static function Auth( $channelid, $userid ) {
            global $db;

            if ( $channelid == 0 ) {
                return true;
            }

            $query = $db->Prepare(
                "SELECT
                    `participant_channelid`
                FROM
                    :chatparticipants
                WHERE
                    `participant_channelid` = :channelid
                    AND `participant_userid` = :userid
                LIMIT 1" 
            );
            $query->BindTable( 'chatparticipants' );
            $query->Bind( 'channelid', $channelid );
            $query->Bind( 'userid', $userid );
            $res = $query->Execute();

            return $res->Results() > 0;
        }
		public static function FindByUserid( $userid ) {
			global $db;
			
			w_assert( is_int( $userid ) );
			
			$query = $db->Prepare(
				'SELECT
					`channel_id`,
                    me.`participant_x` AS x, me.`participant_y` AS y,
                    me.`participant_w` AS w, me.`participant_h` AS h,
                    `user_name`, `user_id`, `user_avatarid`
				FROM
					:chatchannels
						CROSS JOIN :chatparticipants AS me
							ON `channel_id`=me.`participant_channelid`
						CROSS JOIN :chatparticipants AS other
							ON `channel_id`=other.`participant_channelid`
						CROSS JOIN :users
							ON other.`participant_userid`=`user_id`
				WHERE
					me.`participant_userid` = :userid
                    AND me.`participant_active` = 1
					AND other.`participant_userid` != :userid'
			);
			$query->BindTable( 'chatchannels', 'chatparticipants', 'users' );
			$query->Bind( 'userid', $userid );
			$res = $query->Execute();
			
			$channels = array();
			
			while ( $row = $res->FetchArray() ) {
				if ( !isset( $channels[ $row[ 'channel_id' ] ] ) ) {
					$channels[ $row[ 'channel_id' ] ] = array(
						'authtoken' => $row[ 'channel_authtoken' ],
                        'x' => $row[ 'x' ], 'y' => $row[ 'y' ],
                        'w' => $row[ 'w' ], 'h' => $row[ 'h' ],
						'participants' => array()
					);
                    /*
                        TODO: Optimize; use a secondary table called
                              chatrecent that contains the most recent 10
                              messages from each channelid, so that we can
                              do just one query with a WHERE channel_id IN ( .. ) LIMIT 10 * N
                              clause.

                        TODO: Optimize; do not join with bulk table directly; use
                              one more query with an IN clause.
                    */
                    $query = $db->Prepare(
                        'SELECT
                            user_name, bulk_text
                        FROM
                            :shoutbox
                            CROSS JOIN :bulk
                                ON shout_bulkid = bulk_id
                            CROSS JOIN :users
                                ON shout_userid = user_id
                        WHERE
                            shout_channelid = :channelid
                        ORDER BY
                            shout_id DESC
                        LIMIT 10'
                    );
                    $query->BindTable( 'shoutbox', 'bulk' );
                    $query->Bind( 'channelid', $row[ 'channel_id' ] );
                    $res2 = $query->Execute();
                    while ( $message = $res2->FetchArray() ) {
                        $channels[ $row[ 'channel_id' ] ][ 'message' ] = array(
                            'name' => $message[ 'user_name' ],
                            'text' => $message[ 'bulk_text' ]
                        );
                    }
				}
				$channels[ $row[ 'channel_id' ] ][ 'participants' ][] = array(
					'id' => $row[ 'user_id' ],
					'name' => $row[ 'user_name' ],
					'avatar' => $row[ 'user_avatarid' ]
				);
			}

			
			return $channels;
		}
		public static function FindParticipantsByChannel( $channelid ) {
			global $db;
			
			w_assert( is_int( $channelid ) );
			
			$query = $db->Prepare(
				'SELECT
					`user_id`, `user_authtoken`
				FROM
					:chatparticipants CROSS JOIN :users
						ON `participant_userid` = `user_id`
				WHERE
					`participant_channelid` = :channelid'
			);
			$query->BindTable( 'chatparticipants', 'users' );
			$query->Bind( 'channelid', $channelid );
			$res = $query->Execute();
			
			$userinfo = array();
			while ( $row = $res->FetchArray() ) {
				$userinfo[] = $row[ 'user_id' ] . 'x' . substr( $row[ 'user_authtoken' ], 0, 10 );
			}
			
			return $userinfo;
		}
	}

    function Chat_UpdateParticipant( $channelid, $userid, $x, $y, $w, $h, $deactivate ) {
        global $db;

        w_assert( is_int( $channelid ) );
        w_assert( $channelid > 0 );
        w_assert( is_int( $userid ) );
        w_assert( $userid > 0 );
        w_assert( is_int( $x ) );
        w_assert( $x >= 0 );
        w_assert( is_int( $y ) );
        w_assert( $y >= 0 );
        w_assert( is_int( $w ) );
        w_assert( $w >= 0 );
        w_assert( is_int( $h ) );
        w_assert( $h >= 0 );
        w_assert( is_bool( $deactivate ) );

        $updates = array();
        if ( $x > 0 ) {
            $updates[] = '`participant_x` = :x';
        }
        if ( $y > 0 ) {
            $updates[] = '`participant_y` = :y';
        }
        if ( $w > 0 ) {
            $updates[] = '`participant_w` = :w';
        }
        if ( $h > 0 ) {
            $updates[] = '`participant_h` = :h';
        }
        if ( $deactivate === true ) {
            $updates[] = '`participant_active` = 0';
        }
        w_assert( count( $updates ) );
        $query = $db->Prepare(
            'UPDATE
                :chatparticipants
            SET '
            . implode( ', ', $updates )
            . '
            WHERE
                `participant_channelid` = :channelid
                AND `participant_userid` = :userid
            LIMIT 1'
        );
        $query->BindTable( 'chatparticipants' );
        $query->Bind( 'x', $x );
        $query->Bind( 'y', $y );
        $query->Bind( 'w', $w );
        $query->Bind( 'h', $h );
        $query->Bind( 'channelid', $channelid );
        $query->Bind( 'userid', $userid );
        $query->Execute();
    }

    function Chat_Create( $userid1, $userid2 ) {
        global $db;

        // check if userid1 and userid2 are already chatting in an existing channel_id
        // but make sure that no other people are in that convo
        $query = $db->Prepare(
            'SELECT
                channel_id
            FROM
                :chatchannels
                CROSS JOIN :chatparticipants AS one
                    ON channel_id = one.participant_channelid
                    AND one.participant_userid = :userid1
                CROSS JOIN :chatparticipants AS two
                    ON channel_id = two.participant_channelid
                    AND two.participant_userid = :userid2
                CROSS JOIN :chatparticipants AS others
                    ON channel_id = others.participant_channelid
                    AND NOT others.participant_userid IN ( :userid1, :userid2 )
            WHERE
                others.participant_userid IS NULL
            LIMIT 1'
        );
        $query->BindTable( 'chatchannels', 'chatparticipants' );
        $query->Bind( 'userid1', $userid1 );
        $query->Bind( 'userid2', $userid2 );
        $res = $query->Execute();
        if ( $res->Results() ) {
            $row = $res->FetchArray();
            $channelid = $row[ 'channel_id' ];

            // participant #1 who initiated the chat must be shown a chat window,
            // so activate his participation,
            // however, we don't need to activate #2 who is just a passive receiver,
            // until a message is received
            $query = $db->Prepare(
                'UPDATE 
                    :chatparticipants
                SET
                    participant_active = 1
                WHERE
                    participant_userid = :userid1
                    AND participant_channelid = :channelid
                LIMIT 1'
            );
            $query->BindTable( 'chatparticipants' );
            $query->Bind( 'userid1', $userid1 );
            $query->Bind( 'userid2', $userid2 );
            $query->Execute();
        }
        else {
            $query = $db->Prepare(
                'INSERT INTO 
                    :chatchannels
                ( channel_created ) VALUES ( NOW() )'
            );
            $query->BindTable( 'chatchannels' );
            $change = $query->Execute();
            $channelid = $change->InsertId();
            $query = $db->Prepare(
                'INSERT INTO
                    :chatparticipants
                ( participant_userid, participant_channelid, participant_active, praticipant_joined ) VALUES
                ( :userid1, :channelid, :active1, NOW() ), ( :userid2, :channelid, :active2, NOW() )'
            );
            $query->BindTable( 'chatparticipants' );
            $query->Bind( 'userid1', $userid1 );
            $query->Bind( 'userid2', $useird2 );
            $query->Bind( 'channelid', $channelid );
            $query->Bind( 'active1', 1 );
            $query->Bind( 'active2', 0 );
            $query->Execute();
        }
        
        return $channelid;
    }
?>
