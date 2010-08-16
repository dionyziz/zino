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

        public static function Insert( $userid, $songid ) {
			clude( 'music/grooveshark.php' );
			clude( 'music/GSAPI.php' );
			$gsapi = GSAPI::getInstance(array('APIKey' => "1100e42a014847408ff940b233a39930" ) );
			$songid = ( int )$songid;
			$userid = ( int )$userid;
			$info = $gsapi->songAbout( $songid );
			if ( !isset( $info[ 'songID' ] ) ) {
				throw New Exception( "Could not retrive the song" );
			}
			return db(
					'INSERT INTO `song`
                 ( `song_songid`, `song_albumid`, `song_artistid`, `song_userid`, `song_created` )
                 VALUES ( :songid,:albumid, :artistid, :userid, NOW() )',
                 array( 'songid' => $info[ "songID" ], 'albumid' => $info[ "albumID" ], 'artistid' => $info[ "artistID" ], 'userid' => $userid )
                );
        }
    }
?>
