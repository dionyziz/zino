<?php
    class ElementUserProfileSidebarWho extends Element {
        protected $mPersistent = array( 'theuserid' , 'avatarid' );
        public function Render( $theuser , $theuserid , $avatarid ) {
            ?><h2><?php
                if ( $avatarid != 0 ) {
                    ?><a href="?p=photo&amp;id=<?php
                    echo $avatarid;
                    ?>"><?php
                }
                Element( 'user/avatar' , $avatarid , $theuserid , $theuser->Avatar->Width , $theuser->Avatar->Height , $theuser->Name , 150 , '' , 'margin-bottom:5px' , false , 0 , 0 );
                if ( $avatarid != 0 ) {
                    ?></a><?php
                }
                ?><span class="name"><?php
                Element( 'user/name' , $theuserid , $theuser->Name , $theuser->Subdomain , false );
                ?></span>
            </h2><?php
        }
    }
?>
