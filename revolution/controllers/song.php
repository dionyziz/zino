<?php
    class ControllerSong {
        public function Listing( $query, $randomlist = 0 ) {
			clude( "models/music/song.php" );
			clude( 'models/music/grooveshark.php' );
			if ( $randomlist != 0 ) {
				$songs = Song::RandomList();
				Template( 'song/smalllisting', compact( 'songs' ) );
				return;
			}
            $songs = Grooveshark_SearchSong( $query );
            Template( 'song/listing', compact( 'songs', 'query' ) );
        }
    }

?>
