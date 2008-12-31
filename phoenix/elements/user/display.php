<?php
    class ElementUserDisplay extends Element {
        protected $mPersistent = array( 'theuserid' , 'avatarid' );

        public function Render( $theuserid , $avatarid , $theuser ) {
            ?><a href="<?php
            Element( 'user/url' , $theuserid , $theuser->Subdomain );
            ?>"><?php
            Element( 'user/avatar' , $avatarid , $theuserid , $theuser->Avatar->Width , $theuser->Avatar->Height , $theuser->Name , 100 , 'avatar' , '' , true , 50 , 50 );
            Element( 'user/name' , $theuserid , $theuser->Name , $theuser->Subdomain , false );
            ?></a><?php
        }
    }
?>
