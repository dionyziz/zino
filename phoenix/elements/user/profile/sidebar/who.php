<?php
    class ElementUserProfileSidebarWho extends Element {
        public function Render( $theuser ) {
            ?><h2><?php
                Element( 'user/avatar' , $theuser->Avatar->Id , $theuser->Id , $theuser->Avatar->Width , $theuser->Avatar->Height , $theuser->Name , 150 , '' , 'margin-bottom:5px' , false , 0 , 0 );
                ?><span class="name"><?php
                Element( 'user/name' , $theuser->Id , $theuser->Name , $theuser->Subdomain , false );
                ?></span>
            </h2><?php
        }
    }
?>
