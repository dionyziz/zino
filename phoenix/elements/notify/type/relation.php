<?php
    class ElementNotifyTypeRelation extends Element {
        public function Render( $notif ) {
            global $libs;
            global $user;
            
            $libs->Load( 'relation/relation' );
            
            ?><a class="item" href="<?php
                Element( 'user/url' , $notif->Fromuserid , $notif->FromUser->Subdomain );
            ?>"><?php
            
            if ( $notif->User->Avatarid > 0 ) {
                ?><div class="avatar"><?php
                    Element( 'image/view', $notif->User->Avatarid, $notif->Userid, 50, 50, IMAGE_CROPPED_150x150, '', $notif->User->Name, '', true, 50, 50, 0 );
                ?></div><?php
            }
            if ( $notif->FromUser->Gender == 'f' ) {
                ?>Η<?php
            }
            else {
                ?>Ο<?php
            }
            ?> <span class="username"><?php
                echo htmlspecialchars( $notif->FromUser->Name );
            ?></span>
            σε πρόσθεσε στους φίλους<?php
            
            $finder = New FriendRelationFinder();
            $res = $finder->FindFriendship( $user , $notif->FromUser );
            if ( !$res ) {
                ?><div class="addfriend" id="addfriend_<?php
                echo $notif->Fromuserid;
                ?>">
                <a href="" onclick="return Notification.AddFriend( '<?php
                echo $notif->Id;
                ?>' , '<?php
                echo $notif->Fromuserid;
                ?>' )">
                <span class="s1_0061">&#160;</span>
                Πρόσθεσέ τ<?php
                if ( $notif->FromUser->Gender == 'f' ) {
                    ?>η<?php
                }
                else {
                    ?>o<?php
                } 
                ?>ν και εσύ!</a></div><?php
            }
            ?></a><?php
        }
    }
?>