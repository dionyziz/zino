<?php
    class ElementApiAuth extends Element {
        public function Render( tText $username, tText $password ) {
            global $libs;
            $libs->Load( 'user/user' );
            
            $userfinder = New UserFinder();
            $user = $userfinder->FindByNameAndPassword( $username->Get(), $password->Post() );
            
            if ( $user !== false ) {
                $apiarray[ 'id' ] = $user->Id;
                $apiarray[ 'auth' ] = $user->Authtoken;
            } else
            {
                $apiarray[ 'error' ][ 'description' ] = "No such user";
            }
            if ( !$xml ) {
                echo w_json_encode( $apiarray );
            }
            else {
                echo 'XML Zino API not yet supported';
            }
        }
    }
?>