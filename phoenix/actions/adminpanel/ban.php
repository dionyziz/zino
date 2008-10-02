<?php
    function ActionAdminpanelBan( tText $username, tText $reason ) {
        global $libs;
        
        $username = $username->Get();
        $reason = $reason->Get();
        
        if ( $reason == "" ) {
            $reason = "Δεν αναφέρθηκε";
        }
        
        $libs->Load( 'adminpanel/ban' );
        
        $ban = new Ban();
        $res = $ban->BanUser( $username, $reason );
           
        return Redirect( '?p=banlist' );
    }
?>
