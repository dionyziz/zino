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
		public static function RandomList( $amount ) {
			clude( "models/db.php" );
			return db_array(
					'SELECT 
						song_id AS id, song_songid AS songid
					FROM  
						`song` 
					WHERE  
						`song_songid` !=0
					ORDER BY RAND( ) 
					LIMIT 0,:amount', compact( 'amount' )
                );
		}

        public static function Insert( $userid, $songinfo ) {
			clude( 'models/music/grooveshark.php' );
			clude( 'models/music/GSAPI.php' );
			//$gsapi = GSAPI::getInstance(array('APIKey' => "1100e42a014847408ff940b233a39930" ) );
            if ( !is_array( $songinfo ) 
                 || !is_numeric( $songinfo[ 'artistid' ] ) 
                 || !is_numeric( $songinfo[ 'songid' ] )
                 || !is_numeric( $songinfo[ 'albumid' ] ) ) {
                throw New Exception( "problem with song update" );
            }
			$userid = ( int )$userid;
			//$info = $gsapi->songAbout( $songid );
			return db(
					'INSERT INTO `song`
                 ( `song_songid`, `song_albumid`, `song_artistid`, `song_userid`, `song_created` )
                 VALUES ( :songid,:albumid, :artistid, :userid, NOW() )',
                 array( 'songid' => $songinfo[ "songid" ], 'albumid' => $songinfo[ "albumid" ], 'artistid' => $songinfo[ "artistid" ], 'userid' => $userid )
                );
        }
    }
?>
