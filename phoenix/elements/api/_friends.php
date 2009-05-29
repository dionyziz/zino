<?php
    class ElementApiFriends extends Element {
        public function Render( $subdomain ) {
            global $libs;
            global $page;
            
            $libs->Load( 'user/user' );
            $libs->Load( 'relation/relation' );
            
            $userfinder = New UserFinder();
            $user = $userfinder->FindBySubdomain( $subdomain );
            

            if ( $user !== false ) {
                $finder = New FriendRelationFinder();
                $friends = $finder->FindByUser( $theuser , ( $pageno - 1 )*24 , 24 );
                if ( $friends !== false ) {
                    foreach ( $friends as $friend ) {
                        $apiarray[] = $friend->Subdomain;
                    }
                }
                if ( !$xml ) {
                    echo htmlspecialchars( w_json_encode( $apiarray ) );
                }
                else {
                    echo 'XML Zino API not yet supported';
                }
            }
        }
    }
?>