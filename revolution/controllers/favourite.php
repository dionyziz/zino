<?php
    class ControllerFavourite {
        public static function Create( $typeid, $itemid ) {
            isset( $_SESSION[ 'user' ] ) or die;
            clude( 'models/db.php' );
            clude( 'models/favourite.php' );
            Favourite::Create( $_SESSION[ 'user' ][ 'id' ], $typeid, $itemid );
        }
		public static function Listing( $username, $offset = 0, $limit = 100 ) {
            clude( 'models/db.php' );
            clude( 'models/favourite.php' );
            clude( 'models/user.php' );
            
            $user = User::ItemByName( $username );
            if ( empty( $user ) ) {                    
               return;
            }
            $favourites = Favourite::ListByUser( $user[ 'id' ], $offset, $limit );
			arsort( $favourites );
            include "views/favourite/listing.php";
        }
        public static function Delete( $type, $itemid ) {
            isset( $_SESSION[ 'user' ] ) or die;

            clude( 'models/db.php' );
            clude( 'models/favourite.php' );
            clude( 'models/types.php' );

            switch ( $type ) {
                case 'photo':
                    $typeid = TYPE_PHOTO;
                    break;
                case 'poll':
                    $typeid = TYPE_POLL;
                    break;
                case 'journal':
                    $typeid = TYPE_JOURNAL;
                    break;
                default:
                    throw new Exception( "unknown type" );
            }

            $error = '';
            try {
                Favourite::Delete( $_SESSION[ 'user' ][ 'id' ], $typeid, $itemid );
            }
            catch ( Exception $e ) {
                $error = $e->getMessage();
            }

            Template( 'favourite/delete', compact( 'error', 'typeid', 'itemid' ) );
        }
    }
?>
