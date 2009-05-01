<?php
    function UnitBackendNotificationCommentCreated( Comment $comment ) {
        global $libs;
        
        $libs->Load( 'event' );
        $libs->Load( 'notify' );
        
        $event = New Event();
        $event->Typeid = EVENT_COMMENT_CREATED;
        $event->Itemid = $comment->Id;
        $event->Created = $comment->Created;
        $event->Userid = $comment->Userid;
        $event->Save();
        
        $finder = New NotificationFinder();
        $finder->DeleteByCommentAndUser( $comment->Parent, $comment->User );

        return false;
    }
?>
