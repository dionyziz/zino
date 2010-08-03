<?php
    class ControllerNews {
        public static function Listing() {    
            clude( 'models/db.php' );
            clude( 'models/poll.php' );
            clude( 'models/journal.php' );
            clude( 'models/photo.php' );
            $polls = Poll::ListRecent( 25 );
            $journals = Journal::ListRecent( 25 );
            $photos = Photo::ListRecent( 0, 25 );
            $content = array();
            $i = 0;
            foreach ( $polls as $poll ) {
                $content[ $i ] = $poll;
                $content[ $i ][ 'type' ] = 'poll';
                ++$i;
            }
            foreach ( $journals as $journal ) {
                $content[ $i ] = $journal;
                $content[ $i ][ 'type' ] = 'journal';
                ++$i;
            }
            //foreach ( $photos as $photo ) {
            //    $content[ $i ] = $photo;
            //    $content[ $i ][ 'type' ] = 'photo';
            //    ++$i;
            //}
            // shuffle( $content );
            // shuffle( $content );
            global $settings;
            usort( $content, array( self, 'Compare' ) );
            include 'views/news/listing.php';
        }
        private static function Compare( $a, $b ) {
            return $a[ 'id' ] < $b[ 'id' ];
        }
    }
?>
