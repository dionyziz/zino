<?php
    function UnitBackendNotificationFriendDeleted( FriendRelation $relation ) {
        global $libs;
        
        $libs->Load( 'notify' );
        
        $finder = New NotificationFinder();
        $finder->DeleteByEntity( $relation );
        
        return false;
    }
?>
