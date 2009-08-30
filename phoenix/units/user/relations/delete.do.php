<?php
    function UnitUserRelationsDelete( tInteger $userid ) {
        global $user;
        global $libs;
        
        $libs->Load( 'relation/relation' );
        $finder = New FriendRelationFinder;
        $relation = $finder->FindFriendship( $user->Id, $userid );
        
        if ( $relation !== false ) {
            die( '.' . gettype( $relation ) );
            $relation->Delete();
            Element::ClearFromCache( 'user/profile/main/friends' , $user->Id );
        }
    }
?>
