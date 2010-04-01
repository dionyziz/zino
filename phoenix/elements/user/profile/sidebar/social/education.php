<?php
    class ElementUserProfileSidebarSocialEducation extends Element {
        public function Render( $education ) {
            if ( $education!= '-' ) {
                ?><li><strong>scho0l</strong>
                <?php
                Element( 'user/trivial/education' , $education );
                ?></li><?php
            }
        }
    }
?>
