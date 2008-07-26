<?php
    class ElementUserProfileSidebarSocialDrinker extends Element {
        protected $mPersistent = array( 'drinker' );

        public function Render( $drinker ) {
            if ( $drinker != '-' ) {
                ?><li><strong>Πίνεις;</strong>
                <?php
                Element( 'user/trivial/yesno' , $drinker );
                ?></li><?php
            }
        }
    }
?>
