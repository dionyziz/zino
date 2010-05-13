<?php
    class ControllerPhoto {
        public static function Listing( $userid ) {
            $userid = ( int )$userid;
            include_fast( 'models/db.php' );
            include_fast( 'models/interest.php' );
            $interests = Interest::ListByUser( $userid );
            $tags = array();
            foreach ( $interests as $interest ) {
                $tags[ $interest[ 'typeid' ] ][]= $interest[ 'text' ];
            }
            include 'views/interest/listing.php';
        }
    }
?>
:
