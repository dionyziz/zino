<?php
    function UnitBackendNotificationImageTagCreated( ImageTag $tag ) {
        global $libs;
        
        $libs->Load( 'notify' );

        $notification = New Notification();
        $notification->Typeid = EVENT_IMAGETAG_CREATED;
        $notification->Itemid = $tag->Id;
        $notification->Userid = $tag->Ownerid;
        $notification->Save();
        
        return false;
    }
?>
