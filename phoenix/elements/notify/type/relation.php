<?php
    class ElementNotifyTypeRelation extends Element {
        public function Render( $notif ) {
            global $libs;
            global $user;
            
            $libs->Load( 'relation/relation' );
            
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
                ?>"><a href="" onclick="return Notification.AddFriend( '<?php
                echo $notif->Id;
                ?>' , '<?php
                echo $notif->Fromuserid;
                ?>' )">Πρόσθεσέ τ<?php
                if ( $notif->FromUser->Gender == 'f' ) {
                    ?>η<?php
                }
                else {
                    ?>o<?php
                } 
                ?>ν στους φίλους</a></div><?php
            }
            ?><div class="viewprofile"><a href="" onclick="return Notification.Visit( '<?php
            Element( 'user/url' , $notif->Fromuserid , $notif->FromUser->Subdomain );
            ?>' , '0' , '<?php
            echo $notif->Id;
            ?>' , '0' )">Προβολή προφίλ&raquo;</a></div><?php
            
        }
    }
?>