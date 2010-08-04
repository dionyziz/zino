<?php

    class ControllerSong {
        public function Listing( $query ) {
            clude( 'models/music/grooveshark.php' );
            $songs = Grooveshark_SearchSong( $query );
            Template( 'song/listing', compact( 'songs', 'query' ) );
        }
    }

?>
