<?php
    class ElementUserProfileSidebarSocialEducation extends Element {
        protected $mPersistent = array( 'education' );

        public function Render( $education ) {
            if ( $education!= '-' ) {
                ?><li><strong>Μόρφωση</strong>
                <?php
                Element( 'user/trivial/education' , $education );
                ?></li><?php
            }
        }
    }
?>
