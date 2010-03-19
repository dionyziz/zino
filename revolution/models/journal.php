<?php

	class Journal {
		public static function ListRecent( $amount ) { //<---TODO
		    $res = db(
                'SELECT
					`poll_id` as id, `poll_question` as question, `poll_url` as url, `poll_userid` as userid, `poll_created` as created , `poll_numvotes` as numvotes, `poll_numcomments` as numcomments 
				FROM 
					`polls`
				CROSS JOIN `users` ON
					`poll_userid` = `user_id`
				WHERE `poll_delid` = 0
				ORDER BY `poll_id` DESC
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
			
			$item = array();
			$item = mysql_fetch_array( $res );
			return $item;
		}
	}
?>