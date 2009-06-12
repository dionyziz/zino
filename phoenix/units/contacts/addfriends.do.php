<?php
    function UnitContactsAddfriends( tText $ids ) {
        global $libs;
        global $user;
        $libs->Load( 'relation/relation' );
        
        $ids = $ids->Get();
        $userids = explode( " ", $ids );
        if ( $user->Exists() && sizeof( $ids ) != 0 ) {
			$friends = 0;
            foreach( $userids as $userid ){
				if ( $user->Id == $userid ){
                    continue;
				}
                $theuser = New User( $userid );
                if ( $theuser->Exists() ) {
					$friendFinder = new FriendRelationFinder();
					$friendship = $friendFinder->IsFriend( $user, $theuser );
					if ( $friendship == 1 || $friendship == 3 ){
						continue;
					}
                    $relation = New FriendRelation();
                    $relation->Userid = $user->Id;
                    $relation->Friendid = $theuser->Id;
                    $relation->Typeid = 3;
                    $relation->Save();
                    Element::ClearFromCache( 'user/profile/main/friends' , $user->Id );
					++$friends;
                }
            }
			?>contacts.message( "Πρόσθεσες <?php
			echo $friends;
			if ( $friends == 1 ){
				?> φίλο."<?php
			}
			else{
				?> φίλους."<?php
			}
			?>, contacts.previewContactsNotInZino );<?php
			return;
        }
        ?>contacts.previewContactsNotInZino();<?php
    }
?>
