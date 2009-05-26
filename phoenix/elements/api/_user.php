<?php
    class ElementApiUser extends Element {
        public function Render( tText $subdomain ) {
            global $libs;
            global $page;
            $libs->Load( 'user/user' );
            $userfinder = New UserFinder();
            $user = $userfinder->FindBySubdomain( $subdomain );
            if ( $user !== false ) {
                $apiarray[ 'name' ] = $user->Name;
                $apiarray[ 'subdomain' ] = $user->Subdomain;
                $apiarray[ 'age' ] = $user->Profile->Age;
                $apiarray[ 'location' ] = $user->Profile->Location->Name;
                $apiarray[ 'gender' ] = $user->Gender;
                echo htmlspecialchars( w_json_encode( $apiarray ) );
            }
        }
    }
?>