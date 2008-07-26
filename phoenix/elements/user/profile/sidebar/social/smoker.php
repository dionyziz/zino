<?php
    class ElementUserProfileSidebarSocialSmoker extends Element {
        protected $mPersistent = array( 'smoker' );

        public function Render( $smoker ) {
            if ( $smoker != '-' ) {
                ?><li><strong>Καπνίζεις;</strong>
                <?php
                Element( 'user/trivial/yesno' , $smoker );
                ?></li><?php
            }
        }
    }
?>
