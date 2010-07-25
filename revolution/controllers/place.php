<?php
    class ControllerPlace {
        public static function Listing() {
            clude( 'models/db.php' );
            clude( 'models/place.php' );

            return Place::Listing();
        }
    }
?>
