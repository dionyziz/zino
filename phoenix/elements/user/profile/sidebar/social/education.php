<?php
    class ElementUserProfileSidebarSocialEducation extends Element {
        public function Render( $theuser ) {
            if ( $theuser->Profile->Education != '-' ) {
                ?><li><strong>Μόρφωση</strong>
                <?php
                Element( 'user/trivial/education' , $theuser->Profile->Education );
                ?></li><?php
            }
        }
    }
?>
