<?php
    class ElementUserProfileSidebarSocialPolitics extends Element {
        protected $mPersistent = array( 'politics', 'gender' );

        public function Render( $politics, $gender ) {
            if ( $politics != '-' ) {
                ?><li><strong>Πολιτική ιδεολογία</strong><?php
                Element( 'user/trivial/politics', $politcs, $gender );
                ?></li><?php
            }
        }
    }
?>
