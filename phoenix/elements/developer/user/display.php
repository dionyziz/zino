<?php
    class ElementDeveloperUserDisplay extends Element {
        protected $mPersistent = array( 'theuserid' , 'avatarid', 'islink' );
        
        public function Render( $theuserid , $avatarid , $theuser, $islink = true ) {
            global $libs;
            
            $libs->Load( 'image/image' ); // user->Avatar

            if ( $islink ){
                ?><a href="<?php
                ob_start();
                Element( 'developer/user/url' , $theuserid , $theuser->Subdomain );
                echo htmlspecialchars( ob_get_clean() );
                ?>"><?php
            }
            else{
                ?><span><?php
            }
            Element( 'developer/user/avatar' , $avatarid , $theuserid , $theuser->Avatar->Width , $theuser->Avatar->Height , $theuser->Name , 100 , 'avatar' , '' , true , 50 , 50 );
            Element( 'developer/user/name' , $theuserid , $theuser->Name , $theuser->Subdomain , false );
            if ( $islink ){
                ?></a><?php
            }
            else{
                ?></span><?php
            }
        }
    }
?>
