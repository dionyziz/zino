<?php
    class ElementUserProfileSidebarBasicinfo extends Element {
        protected $mPersistent = array( 'theuserid' , 'updated' );
        
        public function Render( $theuser , $theuserid , $updated ) {  
            Element( 'user/profile/sidebar/who', $theuser , $theuserid , $theuser->Avatar->Id );
            Element( 'user/profile/sidebar/slogan', $theuser->Profile->Slogan );
            Element( 'user/profile/sidebar/mood', $theuser->Profile->Mood, $theuser->Profile->Mood->Id, $theuser->Gender );
            Element( 'user/profile/sidebar/info', $theuser );

        }
    }
?>
