<?php
    class ElementUserProfileSidebarView extends Element {
        public function Render( $theuser ) {
            global $rabbit_settings;
            global $libs;
            global $user;
            
            $libs->Load( 'relation/relation' );
            
            ?><div class="sidebar">
                <div class="basicinfo"><?php
                    Element( 'user/profile/sidebar/who', $theuser );
                    Element( 'user/profile/sidebar/slogan', $theuser->Profile->Slogan );
                    Element( 'user/profile/sidebar/mood', $theuser->Profile->Mood, $theuser->Profile->Mood->Id, $theuser->Gender );
                    if ( $user->Id != $theuser->Id && $user->Exists() ) {
                        $finder = New FriendRelationFinder();
                        $res = $finder->FindFriendship( $user , $theuser );
                        if ( !$res ) {
                            ?><div class="addfriend"><a href="" onclick="Profile.AddFriend( '<?php
                            echo $theuser->Id;
                            ?>' );return false;">Προσθήκη στους φίλους</a></div><?php
                        }
                        else {
                            ?><div class="deletefriend"><a href="" onclick="Profile.DeleteFriend( '<?php
                            echo $res->Id;
                            ?>' , '<?php
                            echo $theuser->Id;
                            ?>' );return false;">Διαγραφή από τους φίλους</a></div><?php
                        }
                    }
                    Element( 'user/profile/sidebar/info', $theuser );
                ?></div>
                <div class="look">
                    <img src="<?php
                    echo $rabbit_settings[ 'imagesurl' ];
                    ?>body-male-slim-short.jpg" alt="" /><?php
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
                ?></div>
            </div><?php
        }
    }
?>
