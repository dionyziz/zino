<?php
    class ElementApiAlbums extends Element {
        public function Render( tText $user ) {
            global $libs;
            global $page;
            
            $libs->Load( 'user/user' );
            $libs->Load( 'album' );
            
            $userfinder = New UserFinder();
            $theuser = $userfinder->FindBySubdomain( $user );
            
            if ( $theuser !== false ) {
                $finder = New AlbumFinder();
                $albums = $finder->FindByUser( $theuser, 0, 4000 );
                if ( !empty( $albums ) ) {
                    foreach ( $albums as $album ) {
                        $apiarray[] = $album->Id;
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