<?php
    class ElementUserProfileSidebarSocialDrinker extends Element {
        public function Render( $theuser ) {
            if ( $theuser->Profile->Drinker != '-' ) {
                ?><li><strong>Πίνεις;</strong>
                <?php
                Element( 'user/trivial/yesno' , $theuser->Profile->Drinker );
                ?></li><?php
            }
        }
    }
?>
