<?php
    class ControllerNews {
        public static function Listing() {    
            include 'models/db.php';
            include 'models/poll.php';
            include 'models/journal.php';
            include 'models/photo.php';
            $polls = Poll::ListRecent( 25 );
            $journals = Journal::ListRecent( 25 );
            $photos = Photo::ListRecent( 25 );
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
            foreach ( $photos as $photo ) {
                $content[ $i ] = $photo;
                $content[ $i ][ 'type' ] = 'photo';
                ++$i;
            }
            shuffle( $content );
            global $settings;
            include 'views/news/listing.php';
        }
    }
?>
