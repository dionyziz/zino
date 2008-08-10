<?php
    class ElementUserDisplay extends Element {
        public function Render( User $theuser ) {
            ?><a href="<?php
            Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
            ?>"><?php
            Element( 'user/avatar' , $theuser->Avatar->Id , $theuser->Id , $theuser->Avatar->Width , $theuser->Avatar->Height , $theuser->Name , 100 , 'avatar' , '' , true , 50 , 50 );
            Element( 'user/name' , $theuser->Id , $theuser->Name , $theuser->Subdomain , false );
            ?></a><?php
            
        
        }
    }
?>
