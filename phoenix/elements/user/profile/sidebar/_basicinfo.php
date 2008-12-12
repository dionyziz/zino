<?php
    class ElementUserProfileSidebarBasicinfo extends Element {
        protected $mPersistent = array( 'theuserid', 'updated', 'schoolexists' );
         
        public function Render( $theuser , $theuserid , $updated, $schoolexists ) {  
            Element( 'user/profile/sidebar/who', $theuser , $theuserid , $theuser->Avatar->Id );
            Element( 'user/profile/sidebar/slogan', $theuser->Profile->Slogan );
            Element( 'user/profile/sidebar/mood', $theuser->Profile->Mood, $theuser->Profile->Mood->Id, $theuser->Gender );
            ?><div class="friendedit"><a href=""><span>&nbsp;</span></a></div><?php
            Element( 'user/profile/sidebar/info', $theuser, $schoolexists );
        }
    }
?>
