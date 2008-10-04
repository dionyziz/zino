<?php
    class ElementUserProfileSidebarDetails extends Element {
        protected $mPersistent = array( 'theuserid', 'lastupdated' );
        
        public function Render( $theuser , $theuserid ,  $lastupdated ) {
            ?><div class="look">
				<span class="malebody">&nbsp;</span><?php
                Element( 'user/profile/sidebar/look', $theuser->Profile->Height, $theuser->Profile->Weight,  $theuser->Gender );
            ?></div>
            <div class="social"><?php
                Element( 'user/profile/sidebar/social/view' , $theuser );
            ?></div>
            <div class="aboutme"><?php
                Element( 'user/profile/sidebar/aboutme' , $theuser->Profile->Aboutme, $theuser->Id, $theuser->Profile->Updated );
            ?></div>
            <div class="interests"><?php
                Element( 'user/profile/sidebar/interests' , $theuser );
            ?></div>
            <div class="contacts"><?php
                Element( 'user/profile/sidebar/contacts' , $theuser, $theuser->Id, $theuser->Profile->Updated );
            ?></div><?php
        }
    }
?>
