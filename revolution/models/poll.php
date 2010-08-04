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
        public static function ListByIds( $ids ) {
            if ( empty( $ids ) || !is_array( $ids ) ) {
                return array();
            }

            $res = db(
                'SELECT
                    `poll_id` as id, `poll_question` as question, `poll_url` as url,
                    `poll_created` as created , `poll_numvotes` as numvotes, `poll_numcomments` as numcomments 
                FROM
                    `polls`
                WHERE
                    `poll_id` IN :ids
                    AND `poll_delid` = 0', compact( 'ids' )
            );

            $ret = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $ret[ $row[ 'id' ] ] = $row;
            }

            return $ret;
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
        public static function Create( $userid, $question, $optiontexts ) {
            clude( 'models/url.php' );
            
            is_int( $userid ) or die;
            $url = URL_FormatUnique( $question, $userid, 'Poll::ItemByUrlAndUserid' );

            $poll = array(
                'question' => $question,
                'url' => $url,
                'userid' => $userid,
                'created' => date( 'Y-m-d H:i:s', time() ),
                'delid' => 0,
                'numvotes' => 0,
                'numcomments' => 0
            );

            $res = db( 
                "INSERT INTO `polls`
                        ( `poll_id`, `poll_question`, `poll_url`, `poll_userid`, `poll_created`, `poll_delid`, `poll_numvotes`, `poll_numcomments` )
                    VALUES
                        ( 0, :question, :url, :userid, NOW(), 0, 0, 0 );", 
                $poll
            );
            
            $poll[ 'id' ] = mysql_insert_id();

            $poll[ 'id' ] = mysql_insert_id();

            $poll[ 'options' ] = array();
            foreach ( $optiontexts as $text ) {
                $option = array(
                    'id' => 0,
                    'text' => $text,
                    'numvotes' => 0
                );

                // insert one by one to get ids
                // may be better to insert all together, but they won't be many anyway

                $res = db( 
                    "INSERT INTO `polloptions`
                        ( `polloption_id`, `polloption_text`, `polloption_pollid`, `polloption_numvotes` )
                    VALUES
                        ( 0, :text, :pollid, 0 );",
                    array( 'text' => $text, 'pollid' => $poll[ 'id' ] )
                );

                $option[ 'id' ] = mysql_insert_id();

                $poll[ 'options' ][] = $option;
            }

            return $poll;
        }
        public static function Delete( $id ) {
            return db( 'UPDATE `polls` SET `poll_delid` = 1 WHERE `poll_id` = :id LIMIT 1;', array( 'id' => $id ) );
        }
        public static function ItemByUrlAndUserid( $url, $userid ) {
            $res = db( 
                'SELECT 
                    * 
                FROM 
                    `polls` 
                WHERE 
                    `poll_url` = :url AND
                    `poll_userid` = :userid
                LIMIT 1;', compact( 'url', 'userid' )
            );

            return mysql_fetch_array( $res );
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
				'INSERT INTO `votes` ( `vote_userid`, `vote_created`, `vote_optionid`, `vote_pollid` )
				VALUE 
				( :userid, NOW(), :optionid, :pollid ) ', 
				array( 'pollid' => $pollid, 'userid' => $userid, 'optionid' => $optionid  ) 
			);
			return;
		}
	}
?>
