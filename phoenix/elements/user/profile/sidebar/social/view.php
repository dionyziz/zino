<?php
    class ElementUserProfileSidebarSocialView extends Element {
        public function Render( $theuser ) {
            ?><ul><?php
            Element( 'user/profile/sidebar/social/sex' , $theuser->Profile->Sexualorientation, $theuser->Gender );
			Element( 'user/profile/sidebar/social/relationship' , $theuser->Profile->Relationship, $theuser->Gender );
            Element( 'user/profile/sidebar/social/smoker' , $theuser->Profile->Smoker );
            Element( 'user/profile/sidebar/social/drinker' , $theuser->Profile->Drinker );
            Element( 'user/profile/sidebar/social/education' , $theuser->Profile->Education );
            Element( 'user/profile/sidebar/social/religion' , $theuser->Profile->Religion, $theuser->Gender );
            Element( 'user/profile/sidebar/social/politics' , $theuser->Profile->Politics, $theuser->Gender );
            ?></ul><?php
        } 
    }
?>
