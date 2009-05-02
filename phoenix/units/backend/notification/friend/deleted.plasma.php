<?php
    function UnitBackendNotificationFriendDeleted( FriendRelation $relation ) {
        global $libs;
        
        $libs->Load( 'event' );
        
        $finder = New EventFinder();
        $finder->DeleteByEntity( $relation );
        
        return false;
    }
?>
