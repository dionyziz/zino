<?php
    class ElementApiUser extends Element {
        public function Render( tText $subdomain ) {
            global $libs;
            global $page;
            
            $libs->Load( 'user/user' );
            $libs->Load( 'image/image' );
            
            $userfinder = New UserFinder();
            $user = $userfinder->FindBySubdomain( $subdomain );
            if ( $user !== false ) {
                $apiarray[ 'id' ] = $user->Id;
                $apiarray[ 'name' ] = $user->Name;
                $apiarray[ 'subdomain' ] = $user->Subdomain;
                $apiarray[ 'age' ] = $user->Profile->Age;
                $apiarray[ 'location' ] = $user->Profile->Location->Name;
                $apiarray[ 'gender' ] = $user->Gender;
                $apiarray[ 'avatar' ][ 'id' ] = $user->Avatar->Id;
                ob_start();
                Element( 'image/url', $user->Avatar->Id , $user->Id , IMAGE_CROPPED_150x150 );
                $apiarray[ 'avatar' ][ 'thumb150' ] = ob_get_clean();
                echo htmlspecialchars( w_json_encode( $apiarray ) );
            }
        }
    }
?>