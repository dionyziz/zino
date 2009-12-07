<?php
    class ElementDeveloperUserProfileSidebarBasicinfo extends Element {
        protected $mPersistent = array( 'theuserid', 'updated', 'schoolexists' );
         
        public function Render( $theuser , $theuserid , $updated, $schoolexists ) { 
            global $libs;
            
            $libs->Load( 'mood' );
            
            Element( 'developer/user/profile/sidebar/who', $theuser , $theuserid , $theuser->Avatarid );
            Element( 'developer/user/profile/sidebar/slogan', $theuser->Profile->Slogan );
            Element( 'developer/user/profile/sidebar/mood', $theuser->Profile->Mood, $theuser->Profile->Moodid, $theuser->Gender );
            ?><div class="friendedit"><a href=""><span>&nbsp;</span></a></div><?php
            Element( 'developer/user/profile/sidebar/info', $theuser, $schoolexists );
        }
    }
?>
