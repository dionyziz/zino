<?php
    class ElementUserProfileSidebarSocialPolitics extends Element {
        protected $mPersistent = array( 'politics', 'gender' );

        public function Render( $politics, $gender ) {
            if ( $politics != '-' ) {
                ?><li><strong>p0litika k mlkiec</strong><?php
                Element( 'user/trivial/politics', $politics, $gender );
                ?></li><?php
            }
        }
    }
?>
