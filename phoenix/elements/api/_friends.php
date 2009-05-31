<?php
    class ElementApiFriends extends Element {
        public function Render( tText $user ) {
            global $libs;
            global $page;
            
            $libs->Load( 'user/user' );
            $libs->Load( 'relation/relation' );
            
            $userfinder = New UserFinder();
            $theuser = $userfinder->FindBySubdomain( $user );
            
            if ( $theuser !== false ) {
                $finder = New FriendRelationFinder();
                $friends = $finder->FindByUser( $theuser );
                if ( !empty( $friends ) ) {
                    foreach ( $friends as $friend ) {
                        $apiarray[] = $friend->Friend->Subdomain;
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