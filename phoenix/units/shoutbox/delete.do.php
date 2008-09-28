<?php
    
    function UnitShoutboxDelete( tInteger $shoutid ) {
        global $user;
        global $libs;
        
        $libs->Load( 'shoutbox' );
        
        $shout = New Shout( $shoutid->Get() );
        if ( ( $user->Id == $shout->User->Id && $user->HasPermission( PERMISSION_SHOUTBOX_DELETE ) ) || $user->HasPermission( PERMISSION_SHOUTBOX_DELETE_ALL ) ) {
            $shout->Delete();
        }
    }
?>
