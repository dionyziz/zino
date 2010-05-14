<?php

	class Poll {
		public static function ListRecent( $amount ) {
		    $res = db(
                'SELECT
					`user_name` as username, `user_subdomain` as subdomain, `user_avatarid` as avatarid, `user_gender` as gender,
                    `poll_id` as id, `poll_question` as question, `poll_url` as url,
                    `poll_userid` as userid, `poll_created` as created , `poll_numvotes` as numvotes, `poll_numcomments` as numcomments 
				FROM 
					`polls`
				CROSS JOIN `users` ON
					`poll_userid` = `user_id`
				WHERE 
                    `poll_delid` = 0 AND
                    `user_deleted` = 0
				ORDER BY `poll_id` DESC
				LIMIT :amount', array( 'amount' => $amount ) 
            );
            $polls = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $polls[] = $row;
            }
            return $polls;
        }
        public static function ListByUser( $userid ) {
            return db_array(
                'SELECT
                    `poll_id` as id, `poll_question` as question, `poll_url` as url,
                    `poll_created` as created , `poll_numvotes` as numvotes, `poll_numcomments` as numcomments 
                FROM
                    `polls`
                WHERE
                    `poll_userid` = :userid
                    AND `poll_delid` = 0
                ORDER BY
                    `poll_id` DESC', compact( 'userid' )
            );
        }
		public static function Item( $id ) {
			$res = db(
					'SELECT
						`user_deleted` as userdeleted, `user_name` as username, `user_subdomain` as subdomain,
                        `user_avatarid` as avatarid, `user_gender` as gender,
                        `poll_id` as id, `poll_question` as question, `poll_url` as url,
                        `poll_userid` as userid, `poll_created` as created , `poll_numvotes` as numvotes,
                        `poll_numcomments` as numcomments 
					FROM 
						`polls`
					CROSS JOIN `users` ON
						`poll_userid` = `user_id`
					WHERE `poll_id` = :id
					LIMIT 1', compact( 'id' ) 
			);

            if (  mysql_num_rows( $res ) == 0 ) {
                return false;
            }
			
			$item = array();
			$row = mysql_fetch_array( $res );
            $item = $row;
            $item[ 'user' ] = array(
                'id' => $row[ 'userid' ],
                'name' => $row[ 'username' ],
                'subdomain' => $row[ 'subdomain' ],
                'avatarid' => $row[ 'avatarid' ],
                'gender' => $row[ 'gender' ],
                'deleted' => ( int )$row[ 'userdeleted' ]
            );
			
			$res2 = db(
					'SELECT
						`polloption_id` as id, `polloption_text` as text, `polloption_numvotes` as numvotes
					FROM
						`polloptions`
					WHERE 
						`polloption_pollid` = :id 
					LIMIT 
						0,25',compact( 'id' )
			);
			
			$item[ 'userdeleted' ] = ( int )$item[ 'userdeleted' ];
			$item[ 'options' ] = array();
			while ( $row = mysql_fetch_array( $res2 ) ) {
                $item[ 'options' ][] = $row;
            }
			return $item;
		}
	}
	
	class PollVote {		
		public static function Item( $pollid, $userid ) {
			$res = db(
					'SELECT `vote_optionid`
					FROM `votes`
					WHERE `vote_userid` = :userid
					AND `vote_pollid` = :pollid
					LIMIT 1', array( 'pollid' => $pollid, 'userid' => $userid ) 
			);
			
			$item = mysql_fetch_array( $res );
			if ( $item === false ) {
				return false;
			}	
			return $item[ 'vote_optionid' ];		
		}
		
		public static function Create( $pollid, $optionid, $userid ) {
			$res = db(
				'INSERT IGNORE INTO `votes`
				VALUE 
				( :userid, NOW(), :optionid, :pollid ) ', 
				array( 'pollid' => $pollid, 'userid' => $userid, 'optionid' => $optionid  ) 
			);
			return;
		}
	}
?>
