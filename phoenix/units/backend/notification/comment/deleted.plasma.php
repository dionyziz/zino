<?php
    function UnitBackendNotificationCommentCreated( Comment $comment ) {
        global $libs;
        
        $libs->Load( 'notify' );
        
        $finder = New NotificationFinder();
        $finder->DeleteByEntity( $comment );
        
        return false;
    }
?>
