<?php

    class Status {
        public static function Create( $userid, $text ) {
            if ( !is_numeric( $userid ) ) {
				throw new Exception( "userid should be an integer" );
			}

            $status = array(
                'userid' => $userid,
                'text' => $text
            );

            $res = db( "INSERT INTO `statusbox` ( `statusbox_userid`, `statusbox_message`, `statusbox_created` )
                        VALUES ( :userid, :text, NOW() );", $status );

            $status[ 'id' ] = mysql_insert_id();
            return $status;
        }
    }

?>
