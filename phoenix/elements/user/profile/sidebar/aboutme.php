<?php
    
    class ElementUserProfileSidebarAboutme extends Element {
        protected $mPersistent = array( 'userid', 'updated' );

        public function Render( $aboutme ) {
            if ( $aboutme != '' ) {
                ?><dl><dt><strong>Λίγα λόγια για μένα</strong></dt>
                <dd><?php
                echo htmlspecialchars( $aboutme );
                ?></dd></dl><?php
            }
        }
    }
?>
