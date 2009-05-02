<?php
    function UnitBackendNotificationImageTagDeleted( ImageTag $tag ) {
        global $libs;
        
        $libs->Load( 'notify' );
        
        $finder = New NotificationFinder();
        $notif = $finder->FindByImageTags( $this );

        if ( !is_object( $notif ) ) {
            return;
        }
        
        $notif->Delete();
        
        return false;
    }
?>
