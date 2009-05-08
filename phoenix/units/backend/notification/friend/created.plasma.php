<?php
    function UnitBackendNotificationFriendCreated( FriendRelation $relation ) {
        global $libs;

        $libs->Load( 'notify' );

        $notification = New Notification();
        $notification->Typeid = EVENT_FRIENDRELATION_CREATED;
        $notification->Itemid = $relation->Id;
        $notification->Userid = $relation->Userid;
        $notification->Save();
        
        return false;
    }
?>
