<?php
    class ElementUserProfileSidebarContacts extends Element {
        public function Render( $skype , $msn , $gtalk , $yim ) {
            global $rabbit_settings;
            
            if ( $skype != '' || $msn != '' || $gtalk != '' || $yim != '' ) {
                ?><dl><?php
                    if ( $skype != '' ) {
                        ?><dt class="s1_0005"></dt>
                        <dd><?php 
                        echo htmlspecialchars( $skype );
                        ?></dd><?php
                    }
                    if ( $msn != '' ) {
                        ?><dt class="s1_0010"></dt>
                        <dd><?php
                        echo htmlspecialchars( $msn );
                        ?></dd><?php
                    }
                    if ( $gtalk != '' ) {
                        ?><dt class="s1_0021"></dt>
                        <dd><?php
                        echo htmlspecialchars( $gtalk );
                        ?></dd><?php
                    }
                    if ( $yim != '' ) {
                        ?><dt class="s1_0062"></dt>
                        <dd><?php
                        echo htmlspecialchars( $yim );
                        ?></dd><?php
                    }
                ?></dl><?php
            }
        }
    }
?>
