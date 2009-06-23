<?php
    class ElementApiAvatar extends Element {
        public function Render( tText $user ) {
            global $libs;
            global $page;
            
            $libs->Load( 'user/user' );
            $libs->Load( 'image/image' );
            
            $theuserfinder = New UserFinder();
            $theuser = $theuserfinder->FindBySubdomain( $user->Get() );
            
            if ( $theuser !== false ) {
                $apiarray[ 'id' ] = $theuser->Avatarid;
                ob_start();
                Element( 'image/url', $theuser->Avatarid , $theuser->Id , IMAGE_CROPPED_150x150 );
                $apiarray[ 'thumb150' ] = ob_get_clean();
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