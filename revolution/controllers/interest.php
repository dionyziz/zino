<?php
    class ControllerInterest {
        public static function Listing( $userid ) {
            $userid = ( int )$userid;
            clude( 'models/db.php' );
            clude( 'models/interest.php' );
            $interests = Interest::ListByUser( $userid );
            
            include 'views/interest/listing.php';
        }
        public static function Create( $text ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to create an interest' );
            clude( 'models/db.php' );
            clude( 'models/interest.php' );
            // TODO
        }
        public static function Delete( $text ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to delete an interest' );
            clude( 'models/db.php' );
            clude( 'models/interest.php' );
            // TODO
        }
    }
?>
