<?php
    class ControllerInterest {
        public static function Listing( $userid ) {
            $userid = ( int )$userid;
            clude( 'models/db.php' );
            clude( 'models/interest.php' );
            $interests = Interest::ListByUser( $userid );
            $tags = array();
            foreach ( $interests as $interest ) {
                $tags[ $interest[ 'typeid' ] ][]= $interest[ 'text' ];
            }
            include 'views/interest/listing.php';
        }
    }
?>
