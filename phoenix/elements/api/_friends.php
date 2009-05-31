<?php
    class ElementApiFriends extends Element {
        public function Render( tText $subdomain ) {
            global $libs;
            global $page;
            
            $libs->Load( 'user/user' );
            $libs->Load( 'relation/relation' );
            
            $userfinder = New UserFinder();
            $user = $userfinder->FindBySubdomain( $subdomain );
            

            if ( $user !== false ) {
                $finder = New FriendRelationFinder();
                $friends = $finder->FindByUser( $user );
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