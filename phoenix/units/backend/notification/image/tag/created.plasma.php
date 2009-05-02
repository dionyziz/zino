<?php
    function UnitBackendNotificationImageTagCreated( ImageTag $tag ) {
        global $libs;
        
        $libs->Load( 'event' );

        $event = New Event();
        $event->Typeid = EVENT_IMAGETAG_CREATED;
        $event->Itemid = $tag->Id;
        $event->Userid = $tag->Ownerid;
        $event->Save();
        
        return false;
    }
?>
