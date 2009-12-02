<?php
	/* Avoid Satori/Finder base for speed */
	
	class ChannelFinder {
		public static function FindByUserid( $userid ) {
			global $db;
			
			w_assert( is_int( $userid ) );
			
			$query = $db->Prepare(
				'SELECT
					`channel_id`, `channel_authtoken`, `user_name`, `user_id`, `user_avatarid`
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
						'participants' => array()
					);
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
			
			w_assert( is_int( $userid ) );
			
			$query = $db->Prepare(
				'SELECT
					`participant_userid`
				FROM
					:chatparticipants
				WHERE
					`participant_channelid` = :channelid'
			);
			$query->BindTable( 'chatparticipants' );
			$query->Bind( 'channelid', $channelid );
			$res = $query->Execute();
			
			$userids = array();
			while ( $row = $res->FetchArray() ) {
				$userids[] = $row[ 'participant_userid' ];
			}
			
			return $userids;
		}
	}
?>
