<?php
    class ElementUserName extends Element {
        public function Render( User $theuser, $link = true ) {
            if ( !$link ) {
                echo htmlspecialchars( $theuser->Name );
            }
            else {
                ?><a href="<?php
                Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
                ?>"><?php
                echo htmlspecialchars( $theuser->Name );
                ?></a><?php
            }
        }
    }
?>
