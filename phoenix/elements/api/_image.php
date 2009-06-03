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
            
            if ( $image->Exists() && $image->Userid == $theuser->Id && $image->Album->Ownertype == TYPE_USERPROFILE ) {
                $apiarray[ 'id' ] = $image->Id;
                $apiarray[ 'name' ] = $image->Name;
                $favfinder = New FavouriteFinder();
                $favourites = $favfinder->FindByEntity( $image, 100 );
                if ( !empty( $favourites ) ) {
                    foreach ( $favourites as $favourite ) {
                        $apiarray[ 'favourites' ][] = $favourite->User->Subdomain;
                    }
                }
                $tagfinder = New ImageTagFinder();
                $tags = $tagfinder->FindByImage( $image );
                foreach ( $tags as $tag ) {
                    unset( $tagarray );
                    $person = New User( $tag->Personid );
                    $tagarray[ 'tagged' ] = $person->Subdomain;
                    $tagarray[ 'left' ] = $tag->Left;
                    $tagarray[ 'top' ] = $tag->Top;
                    $tagarray[ 'width' ] = $tag->Width;
                    $tagarray[ 'height' ] = $tag->height;
                    $apiarray[ 'tags' ][] = $tagarray;
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