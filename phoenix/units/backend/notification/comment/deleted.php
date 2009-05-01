<?php
    function UnitBackendNotificationCommentCreated( Comment $comment ) {
        global $libs;
        
        $libs->Load( 'event' );
        
        $finder = New EventFinder();
        $finder->DeleteByEntity( $this );
        
        return false;
    }
?>
