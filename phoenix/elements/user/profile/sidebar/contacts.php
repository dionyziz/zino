<?php
    class ElementUserProfileSidebarContacts extends Element {
        protected $mPersistent = array( 'userid', 'updated' );

        public function Render( $theuser, $userid, $updated ) {
            global $rabbit_settings;
            
            ?><dl><?php
                if ( $theuser->Profile->Skype != '' ) {
                    ?><dt class="skype"></dt>
                    <dd><?php 
                    echo htmlspecialchars( $theuser->Profile->Skype );
                    ?></dd><?php
                }
                if ( $theuser->Profile->Msn != '' ) {
                    ?><dt class="msn"></dt>
                    <dd><?php
                    echo htmlspecialchars( $theuser->Profile->Msn );
                    ?></dd><?php
                }
                if ( $theuser->Profile->Gtalk != '' ) {
                    ?><dt class="gtalk"></dt>
                    <dd><?php
                    echo htmlspecialchars( $theuser->Profile->Gtalk );
                    ?></dd><?php
                }
                if ( $theuser->Profile->Yim != '' ) {
                    ?><dt class="yim"></dt>
                    <dd><?php
                    echo htmlspecialchars( $theuser->Profile->Yim );
                    ?></dd><?php
                }
            ?></dl><?php
        }
    }
?>
