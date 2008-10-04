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
                            ?><div class="addfriend"><a href="" onclick="return Profile.AddFriend( '<?php
                            echo $theuser->Id;
                            ?>' )"><span>&nbsp;</span>Προσθήκη στους φίλους</a></div><?php
                        }
                        else {
                            ?><div class="deletefriend"><a href="" onclick="return Profile.DeleteFriend( '<?php
                            echo $res->Id;
                            ?>' , '<?php
                            echo $theuser->Id;
                            ?>' )"><span>&nbsp;</span>Διαγραφή από τους φίλους</a></div><?php
                        }
                    }
                    Element( 'user/profile/sidebar/info', $theuser );
                ?></div><?php
                Element( 'user/profile/sidebar/details' , $theuser , $theuser->Id , $theuser->Profile->Updated );
            ?></div><?php
        }
    }
?>
