<?php
    class ElementApiUser extends Element {
        public function Render( tText $user ) {
            global $libs;
            global $page;
            
            $libs->Load( 'user/user' );
            $libs->Load( 'image/image' );
            $libs->Load( 'user/profile' );
            
            $theuserfinder = New UserFinder();
            $theuser = $theuserfinder->FindBySubdomain( $user->Get() );
            
            if ( $theuser !== false ) {
                $apiarray[ 'name' ] = $theuser->Name;
                $apiarray[ 'subdomain' ] = $theuser->Subdomain;
                $apiarray[ 'age' ] = $theuser->Profile->Age;
                $apiarray[ 'location' ] = $theuser->Profile->Location->Name;
                $apiarray[ 'gender' ] = $theuser->Gender;
                $apiarray[ 'avatar' ][ 'id' ] = $theuser->Avatarid;
                ob_start();
                Element( 'image/url', $theuser->Avatarid , $theuser->Id , IMAGE_CROPPED_150x150 );
                $apiarray[ 'avatar' ][ 'thumb150' ] = ob_get_clean();
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