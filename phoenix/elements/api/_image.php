<?php
    class ElementApiImage extends Element {
        public function Render( tInteger $imageid, tText $user ) {
            global $libs;
            global $page;
            
            $libs->Load( 'user/user' );
            $libs->Load( 'image/image' );
            $libs->Load( 'favourite' );
            $libs->Load( 'image/tag' );
            
            $userfinder = New UserFinder();
            $theuser = $userfinder->FindBySubdomain( $user->Get() );
            
            $image = New Image( $imageid->Get() );
            
            if ( $image->Exists() && $image->Ownerid == $theuser->Id && $image->Ownertype == TYPE_USERPROFILE ) {
                $apiarray[ 'id' ] = $Image->Id;
                $apiarray[ 'name' ] = $image->Name;
                $favfinder = New FavouritesFinder();
                $favourites = $finder->FindByUserAndEntity( $user, $image );
                if ( !empty( $favourites ) ) {
                    foreach ( $favourites as $favourite ) {
                        $apiarray[ 'favourites' ][] = $favourite->User->Subdomain;
                    }
                }
                if ( !$xml ) {
                    echo w_json_encode( $apiarray );
                }
                else {
                    echo 'XML Zino API not yet supported';
                }
            }
        }
    }
?>