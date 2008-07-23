<?php
    class ElementUserDisplay extends Element {
        public function Render( User $theuser ) {
            ?><a href="<?php
            Element( 'user/url' , $theuser );
            ?>"><?php
            Element( 'user/avatar' , $theuser , 100 , 'avatar' , '' , true , 50 , 50 );
            Element( 'user/name' , $theuser , false );
            ?></a><?php
            
        
        }
    }
?>
