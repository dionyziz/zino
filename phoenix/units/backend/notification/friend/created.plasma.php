<?php
    function UnitBackendNotificationFriendCreated( FriendRelation $relation ) {
        global $libs;

        $libs->Load( 'event' );

        $event = New Event();
        $event->Typeid = EVENT_FRIENDRELATION_CREATED;
        $event->Itemid = $relation->Id;
        $event->Userid = $relation->Userid;
        $event->Save();
        
        return false;
    }
?>
