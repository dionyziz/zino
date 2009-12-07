<?php    
    class ElementDeveloperUserProfileSidebarSocialReligion extends Element {
        protected $mPersistent = array( 'religion', 'gender' );

        public function Render( $religion, $gender ) {
            if ( $religion != '-' ) {
                ?><li><strong>Θρήσκευμα</strong> <?php
                Element( 'developer/user/trivial/religion' , $religion , $gender );
                ?></li><?php
            }
        }
    }
?>
