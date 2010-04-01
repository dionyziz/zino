<?php    
    class ElementUserProfileSidebarSocialRelationship extends Element {
        protected $mPersistent = array( 'relationship' , 'gender' );

        public function Render( $relationship, $gender ) {
            if ( $relationship != '-' ) {
                ?><li><strong>piAsmen0</strong> <?php
                Element( 'user/trivial/relationship' , $relationship , $gender );
                ?></li><?php
            }
        } 
    }
?>
