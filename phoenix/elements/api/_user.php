<?php
    class ElementApiUser extends Element {
        public function Render( tText $subdomain ) {
            global $libs;
            global $page;
            $libs->Load( 'user/user' );
            $userfinder = New UserFinder();
            $user = FindBySubdomain( $subdomain );
            if ( $user !== false ) {
                $apiarray[ 'name' ] = $user->Name;
                $apiarray[ 'subdomain' ] = $user->Subdomain;
                $apiarray[ 'age' ] = $user->Profile->Age;
                $apiarray[ 'location' ] = $user->Profile->Location->Name;
                $apiarray[ 'gender' ] = $user->Gender();
                var_dump( $apiarray );
            }
        }
    }
?>