<?php
    class ElementApiAlbums extends Element {
        public function Render( tText $user ) {
            global $libs;
            global $page;
            
            $libs->Load( 'user/user' );
            $libs->Load( 'poll/poll' );
            
            $userfinder = New UserFinder();
            $theuser = $userfinder->FindBySubdomain( $user->Get() );
            
            if ( $theuser !== false ) {
                $pollfinder = New AlbumFinder();
                $polls = $pollfinder->FindByUserAndUrl( $theuser, $poll, 0, 4000 );
                if ( !empty( $polls ) ) {
                    foreach ( $polls as $poll ) {
                        $votefinder = New PollVoteFinder();
                        //$votes = $votefinder->FindByPollAndUser( $poll, 
                        $apiarray[ 'count' ] = $theuser->Count->Albums;
                        $apiarray[ 'egoalbum' ] = $theuser->Egoalbumid;
                        $apiarray[ 'albums' ][] = $album->Id;
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
