<?php
    function ActionAdminpanelRevoke( tInteger $userid ) {
        global $libs;
        
        $userid = $userid->Get();
        
        $libs->Load( 'adminpanel/ban' );
        
        $ban = New Ban();
        $res = $ban->Revoke( $userid );
    
        return Redirect( '?p=banlist' );
    }
?>
