<?php
    class ControllerPlace {
        public static function Listing() {
            clude( 'models/db.php' );
            clude( 'models/place.php' );

            $places = Place::Listing();

            include 'views/place/listing.php';
        }
    }
?>
