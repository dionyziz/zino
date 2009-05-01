<?php
    function UnitBackendNotificationCommentCreated( Comment $comment ) {
        global $libs;
        
        $libs->Load( 'event' );
        
        $event = New Event();
        $event->Typeid = EVENT_COMMENT_CREATED;
        $event->Itemid = $comment->Id;
        $event->Created = $comment->Created;
        $event->Userid = $comment->Userid;
        $event->Save();
        
        return false;
    }
?>
