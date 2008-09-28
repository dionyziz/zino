<?php
    function ActionAdminpanelRevoke( tInteger $userid ) {
        global $libs;
        
        $userid = $userid->Get();
        
        $libs->Load( 'adminpanel/ban' );
        
        $ban = new Ban();
        $res = $ban->Revoke( $userid );
    
        return Redirect( '?p=banlist' );
    }
?>
