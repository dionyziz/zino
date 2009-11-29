<?php
    function ActionAdminpanelRevoke( tInteger $userid ) {
        global $libs;
        
        $userid = $userid->Get();
        
        $libs->Load( 'adminpanel/ban' );
        
        $res = Ban::Revoke( $userid );
    
        return Redirect( '?p=banlist' );
    }
?>
