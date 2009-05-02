<?php
    function UnitBackendNotificationImageTagDeleted( ImageTag $tag ) {
        global $libs;
        
        $libs->Load( 'notify' );
        
        $finder = New NotificationFinder();
        $notif = $finder->FindByImageTags( $tag );

        if ( !is_object( $notif ) ) {
            return false;
        }
        
        $notif->Delete();
        
        return false;
    }
?>
