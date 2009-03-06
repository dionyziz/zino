<?php
    class ElementUserProfileSidebarContacts extends Element {
        public function Render( $skype , $msn , $gtalk , $yim ) {
            global $rabbit_settings;
            
            if ( $skype != '' || $msn != '' || $gtalk != '' || $yim != '' ) {
                ?><dl><?php
                    if ( $skype != '' ) {
                        ?><dt class="skype"></dt>
                        <dd><?php 
                        echo htmlspecialchars( $skype );
                        ?></dd><?php
                    }
                    if ( $msn != '' ) {
                        ?><dt class="msn"></dt>
                        <dd><?php
                        echo htmlspecialchars( $msn );
                        ?></dd><?php
                    }
                    if ( $gtalk != '' ) {
                        ?><dt class="gtalk"></dt>
                        <dd><?php
                        echo htmlspecialchars( $gtalk );
                        ?></dd><?php
                    }
                    if ( $yim != '' ) {
                        ?><dt class="yim"></dt>
                        <dd><?php
                        echo htmlspecialchars( $yim );
                        ?></dd><?php
                    }
                ?></dl><?php
            }
        }
    }
?>
