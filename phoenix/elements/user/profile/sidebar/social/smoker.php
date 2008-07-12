<?php
	class ElementUserProfileSidebarSocialSmoker extends Element {
        public function Render( $theuser ) {
            if ( $theuser->Profile->Smoker != '-' ) {
                ?><li><strong>Καπνίζεις;</strong>
                <?php
                Element( 'user/trivial/yesno' , $theuser->Profile->Smoker );
                ?></li><?php
            }
        }
    }
?>
