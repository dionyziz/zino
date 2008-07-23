<?php
    
    class ElementUserProfileSidebarAboutme extends Element {
        public function Render( $theuser ) {
            if ( $theuser->Profile->Aboutme !=  '' ) {
                ?><dl><dt><strong>Λίγα λόγια για μένα</strong></dt>
                <dd><?php
                echo htmlspecialchars( $theuser->Profile->Aboutme );
                ?></dd></dl><?php
            }
        }
    }
?>
