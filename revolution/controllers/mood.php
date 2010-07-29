<?php
    class ControllerMood {
        public static function Listing() {
            $moods = Mood::Listing();
            include 'views/mood/listing.php';
        }
        public static function Update( $moodid ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to change your mood' );
            $moodid = ( int )$moodid;

            clude( 'models/db.php' );
            clude( 'models/mood.php' );

            Mood::Update( $_SESSION[ 'user' ][ 'id' ], $moodid );
        }
    }
?>
