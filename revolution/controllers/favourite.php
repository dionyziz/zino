<?php
    class ControllerFavourite {
        public static function Create( $typeid, $itemid ) {
            isset( $_SESSION[ 'user' ] ) or die;
            clude( 'models/db.php' );
            clude( 'models/favourite.php' );
            Favourite::Create( $_SESSION[ 'user' ][ 'id' ], $typeid, $itemid );
        }
		public static function Listing( $username ) {
            clude( 'models/db.php' );
            clude( 'models/favourite.php' );
            clude( 'models/user.php' );
            
            $user = User::ItemByName( $username );
            if ( empty( $user ) ) {                    
               return;
            }
            $favourites = Favourite::ListByUser( $user[ 'id' ] );
            var_dump( $favourites );
            include "views/favourite/listing.php";
        }
    }
?>
