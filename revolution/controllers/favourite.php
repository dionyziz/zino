<?php
    class ControllerFavourite {
        public static function Create( $typeid, $itemid ) {
            isset( $_SESSION[ 'user' ] ) or die;
            include_fast( 'models/db.php' );
            include_fast( 'models/favourite.php' );
            Favourite::Create( $_SESSION[ 'user' ][ 'id' ], $typeid, $itemid );
        }
    }
?>
