<?php
    class ElementFavouriteView extends Element {
        public function Render( tInteger $userid ) {
            global $libs;

            $libs->Load( 'favourite' );
            $libs->Load( 'journal' );
            
            $userid = $userid->Get();
            
            // Find all user's favourite journals
            $favfinder = new FavouriteFinder();
            $favourites = $favfinder->FindByUserAndType( $userid, TYPE_JOURNAL );

            // print what you have found
            foreach ( $favourites as $value ) {
                echo "id: " . $value->Itemid;
            }
        }
    }
?>
