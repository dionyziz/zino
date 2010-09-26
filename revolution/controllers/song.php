<?php
    class ControllerSong {
        public function Listing( $query, $randomlist = 0 ) {
			clude( "models/music/song.php" );
			clude( 'models/music/grooveshark.php' );
			clude( "models/music/tinyurl.php" );
			if ( $randomlist != 0 ) {
				$songlist = Song::RandomList( 5 );
				$ids = array();
				 foreach ( $songlist as $song ) {
				   $ids[] = $song[ 'songid' ];
				}
				$songs = Grooveshark_AboutSongs( $ids );
				$query = "random";
				Template( 'song/listing', compact( 'songs', 'query' ) );
				//$songs = Song::RandomList();
				//Template( 'song/listing', compact( 'songs' ) );
				return;
			}
            //$songs = Grooveshark_SearchSong( $query );
	    $songs = Tinyurl::SearchSong( $query );
            Template( 'song/listing', compact( 'songs', 'query' ) );
        }
    }

?>
