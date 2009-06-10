<?php
    class ElementApiAuth extends Element {
        public function Render( tText $username, tText $password ) {
            global $libs;
            $libs->Load( 'user/user' );
            
            $userfinder = New UserFinder();
            $theuser = $userfinder->FindByNameAndPassword( $username->Get(), $password->Get() );
            
            if ( $theuser !== false ) {
                $apiarray[ 'id' ] = $theuser->Id;
                $apiarray[ 'auth' ] = $theuser->Authtoken;
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