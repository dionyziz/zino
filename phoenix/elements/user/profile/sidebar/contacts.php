<?php
    class ElementUserProfileSidebarContacts extends Element {
        public function Render( $theuser ) {
            global $rabbit_settings;
            
            ?><dl><?php
                if ( $theuser->Profile->Skype != '' ) {
                    ?><dt><img src="<?php
                    echo $rabbit_settings[ 'imagesurl' ];
                    ?>skype.jpg" alt="skype" title="Skype" /></dt>
                    <dd><?php 
                    echo htmlspecialchars( $theuser->Profile->Skype );
                    ?></dd><?php
                }
                if ( $theuser->Profile->Msn != '' ) {
                    ?><dt><img src="<?php
                    echo $rabbit_settings[ 'imagesurl' ];
                    ?>msn.jpg" alt="msn" title="MSN" /></dt>
                    <dd><?php
                    echo htmlspecialchars( $theuser->Profile->Msn );
                    ?></dd><?php
                }
                if ( $theuser->Profile->Gtalk != '' ) {
                    ?><dt><img src="<?php
                    echo $rabbit_settings[ 'imagesurl' ];
                    ?>gtalk.png" alt="gtalk" title="Gtalk" /></dt>
                    <dd><?php
                    echo htmlspecialchars( $theuser->Profile->Gtalk );
                    ?></dd><?php
                }
                if ( $theuser->Profile->Yim != '' ) {
                    ?><dt><img src="<?php
                    echo $rabbit_settings[ 'imagesurl' ];
                    ?>/yahoo.jpg" alt="yahoo" title="Yahoo" /></dt>
                    <dd><?php
                    echo htmlspecialchars( $theuser->Profile->Yim );
                    ?></dd><?php
                }
            ?></dl><?php
        }
    }
?>
