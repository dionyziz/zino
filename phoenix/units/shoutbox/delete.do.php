<?php
    
    function UnitShoutboxDelete( tInteger $shoutid ) {
        global $user;
        global $libs;
        
        $libs->Load( 'chat/message' );
        
        $shout = New Shout( $shoutid->Get() );
        if ( ( $user->Id == $shout->Userid && $user->HasPermission( PERMISSION_SHOUTBOX_DELETE ) ) || $user->HasPermission( PERMISSION_SHOUTBOX_DELETE_ALL ) ) {
            $shout->Delete();
        }
    }
?>
