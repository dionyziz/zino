<?php
    class ElementFavouriteView extends Element {
        public function Render( tText $subdomain ) {
            global $libs;

            $libs->Load( 'favourite' );
            $libs->Load( 'journal' );
            
            $subdomain = $subdomain->Get();
            
            // Find all user's favourite journals
            $userfinder = New UserFinder();
            $theuser = $userfinder->FindBySubdomain( $subdomain );
            $favfinder = New FavouriteFinder();
            $favourites = $favfinder->FindByUserAndType( $theuser->Id, TYPE_JOURNAL );

            // print what you have found
            foreach ( $favourites as $value ) {
                echo "id: " . $value->Itemid;
            }
        }
    }
?>
