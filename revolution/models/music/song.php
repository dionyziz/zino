<?php
    class Song {
        public static function Item( $userid ) {
			clude( "models/db.php" );
            return
                array_shift( db_array(
                     'SELECT
                        song_id AS id, song_songid AS songid
                     FROM
                        song
                     WHERE
                        song_userid = :userid
                     ORDER BY
                        `song_id` DESC
                    LIMIT 1', compact( 'userid' )
                ) );
        }
		public static function RandomList() {
			clude( "models/db.php" );
			return db_array(
					'SELECT 
						song_id AS id, song_songid AS songid
					FROM  
						`song` 
					WHERE  
						`song_songid` !=0
					ORDER BY RAND( ) 
					LIMIT 0,100'
                );
		}
    }
?>
