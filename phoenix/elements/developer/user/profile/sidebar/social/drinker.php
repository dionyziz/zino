<?php
    class ElementDeveloperUserProfileSidebarSocialDrinker extends Element {
        protected $mPersistent = array( 'drinker' );

        public function Render( $drinker ) {
            if ( $drinker != '-' ) {
                ?><li><strong>Πίνεις;</strong>
                <?php
                Element( 'developer/user/trivial/yesno' , $drinker );
                ?></li><?php
            }
        }
    }
?>
