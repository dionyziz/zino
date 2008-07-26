<?php    
    class ElementUserProfileSidebarSocialReligion extends Element {
        protected $mPersistent = array( 'religion', 'gender' );

        public function Render( $religion, $gender ) {
            if ( $theuser->Profile->Religion != '-' ) {
                ?><li><strong>Θρήσκευμα</strong>
                <?php
                Element( 'user/trivial/religion' , $theuser->Profile->Religion , $theuser->Gender );
                ?></li><?php
            }
        }
    }
?>
