<?php    
    class ElementDeveloperUserProfileSidebarSocialRelationship extends Element {
        protected $mPersistent = array( 'relationship' , 'gender' );

        public function Render( $relationship, $gender ) {
            if ( $relationship != '-' ) {
                ?><li><strong>Κατάσταση</strong> <?php
                Element( 'developer/user/trivial/relationship' , $relationship , $gender );
                ?></li><?php
            }
        } 
    }
?>
