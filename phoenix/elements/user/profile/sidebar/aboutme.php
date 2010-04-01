<?php
    
    class ElementUserProfileSidebarAboutme extends Element {

        public function Render( $aboutme ) {
            if ( $aboutme != '' ) {
                ?><dl><dt><strong>Liga l0gia g tn eafto m</strong></dt>
                <dd><?php
                echo htmlspecialchars( $aboutme );
                ?></dd></dl><?php
            }
        }
    }
?>
