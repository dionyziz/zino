<?php
    class ElementDeveloperUserProfileSidebarSocialEducation extends Element {
        public function Render( $education ) {
            if ( $education!= '-' ) {
                ?><li><strong>Μόρφωση</strong>
                <?php
                Element( 'developer/user/trivial/education' , $education );
                ?></li><?php
            }
        }
    }
?>
