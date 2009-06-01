<?php
    class ElementApiAlbums extends Element {
        public function Render( tInteger $albumid, tText $user ) {
            global $libs;
            global $page;
            
            $libs->Load( 'user/user' );
            $libs->Load( 'album' );
            
            $userfinder = New UserFinder();
            $theuser = $userfinder->FindBySubdomain( $user->Get() );
            
            $album = New Album( $id->Get() );
            
            if ( $album->Exists() && $album->Ownerid == $theuser->Id && $album->Ownertype == TYPE_USERPROFILE ) {
                $imagefinder = New ImageFinder();
                $images = $imagefinder->FindByAlbum( $album, 0, 4000 );
                
                if ( !empty( $images ) ) {
                    $apiarray[ 'name' ] = $album->Name;
                    $apiarray[ 'photocount' ] = $album->Numphotos;
                    $apiarray[ 'commentscount' ] = $theuser->Numcomments;
                    foreach ( $images as $image ) {
                        $apiarray[ 'photos' ][] = $photo->Id;
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