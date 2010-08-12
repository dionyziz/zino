<?php

    class ControllerSong {
        public function Listing( $query, $randomlist = 0 ) {
			clude( "models/music/song.php" );
			clude( 'models/music/grooveshark.php' );
			if ( $randomlist != 0 ) {
				$songlist = Song::RandomList();
				var_dump( $songlist );
				$ids = array();
				foreach ( $songlist as $song ) {
					$ids[] = $song[ 'song_id' ];
				}
				$songs = Grooveshark_AboutSongs( $ids );
				$query = "random";
				Template( 'song/listing', compact( 'songs', 'query' ) );
				return;
			}
            $songs = Grooveshark_SearchSong( $query );
            Template( 'song/listing', compact( 'songs', 'query' ) );
        }
    }

?>
