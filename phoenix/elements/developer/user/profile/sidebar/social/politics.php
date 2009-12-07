<?php
    class ElementDeveloperUserProfileSidebarSocialPolitics extends Element {
        protected $mPersistent = array( 'politics', 'gender' );

        public function Render( $politics, $gender ) {
            if ( $politics != '-' ) {
                ?><li><strong>Πολιτική ιδεολογία</strong><?php
                Element( 'developer/user/trivial/politics', $politics, $gender );
                ?></li><?php
            }
        }
    }
?>
