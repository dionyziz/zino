<?php
    class ControllerInterest {
        public static function Listing( $userid ) {
            $userid = ( int )$userid;
            clude( 'models/db.php' );
            clude( 'models/interest.php' );
            $interests = Interest::ListByUser( $userid );
            
            include 'views/interest/listing.php';
        }
    }
?>
