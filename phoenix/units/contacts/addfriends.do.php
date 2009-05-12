<?php
    function UnitContactsAddfriends( tText $ids ) {
        global $libs;
        global $user;
        $libs->Load( 'relation/relation' );
        
        $ids = $ids->Get();
        $userids = explode( " ", $ids );
        if ( $user->Exists() && sizeof( $ids ) != 0 ) {
            foreach( $userids as $userid ){
                $theuser = New User( $userid );
                if ( $theuser->Exists() ) {
                    $relation = New FriendRelation();
                    $relation->Userid = $user->Id;
                    $relation->Friendid = $theuser->Id;
                    $relation->Typeid = 3;
                    $relation->Save();
                }
            }
        }
        ?>
        contacts.previwContactsNotInZino();
        <?php
    }
?>
