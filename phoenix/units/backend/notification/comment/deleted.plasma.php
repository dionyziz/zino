<?php
    function UnitBackendNotificationCommentDeleted( Comment $comment ) {
        global $libs;
        
        $libs->Load( 'notify/notify' );
        
        $finder = New NotificationFinder();
        $finder->DeleteByEntity( $comment );
        
        return false;
    }
?>
