<?php

	class Journal {
		public static function ListRecent( $amount ) { //<---TODO
		    $res = db(
                'SELECT
					`user_name` as username, `user_subdomain` as subdomain, `user_avatarid` as avatarid, `user_gender` as gender, `journal_id` as id, `journal_title` as title, `journal_url` as url, `journal_userid` as userid, `journal_created` as created , `journal_numcomments` as numcomments 
				FROM 
					`journals`
				CROSS JOIN `users` ON
					`journal_userid` = `user_id`
				WHERE 
                    `journal_delid` = 0 AND
                    `user_deleted` = 0
				ORDER BY `journal_id` DESC
				LIMIT :amount', array( 'amount' => $amount ) 
            );
            $polls = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $polls[] = $row;
            }
            return $polls;
        }
		
		public static function Item( $id ) {
			$res = db(
					'SELECT
						`bulk_text` as text, `user_deleted` as userdeleted, `user_name` as username, `user_subdomain` as subdomain, `user_avatarid` as avatarid, `user_gender` as gender, `journal_id` as id, `journal_created` as created, `journal_numcomments` as numcomments, `journal_title` as title, `journal_url` as url, `journal_userid` as userid 
					FROM 
						`journals`
					CROSS JOIN `users` ON
						`journal_userid` = `user_id`
					CROSS JOIN `bulk` ON
						`journal_bulkid` = `bulk_id`
					WHERE `journal_id` = :id
					LIMIT 1', compact( 'id' ) 
			);

            if (  mysql_num_rows( $res ) == 0 ) {
                return false;
            }
			
			$item = array();
			$item = mysql_fetch_array( $res );
            $item[ 'user' ] = array(
                'id' => $item[ 'userid' ],
                'name' => $item[ 'username' ],
                'gender' => $item[ 'gender' ],
                'subdomain' => $item[ 'subdomain' ],
                'avatarid' => $item[ 'avatarid' ],
                'deleted' => ( int )$item[ 'userdeleted' ]
            );
			return $item;
		}
	}
?>
