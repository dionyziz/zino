<?php
    function UnitUserRelationsDelete( tInteger $userid ) {
        global $user;
        global $libs;
        
        $libs->Load( 'relation/relation' );
        $finder = New FriendRelationFinder;
        $relation = $finder->FindFriendship( $user, New User( $userid ) );
        
        if ( $relation !== false ) {
            $relation->Delete();
            Element::ClearFromCache( 'user/profile/main/friends' , $user->Id );
        }
    }
?>
