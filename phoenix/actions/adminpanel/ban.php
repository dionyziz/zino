<?php
    function ActionAdminpanelBan( tText $username, tText $reason, tText $time_banned ) {
        global $libs;
        
        $username = $username->Get();
        $reason = $reason->Get();
        $time_banned = $time_banned->Get();
        
        if ( $reason == "" ) {
            $reason = "Δεν αναφέρθηκε";
        }
        
        $time_banned = $time_banned*24*60*60;
        
        $libs->Load( 'adminpanel/ban' );
        
        $ban = new Ban();
        $res = $ban->BanUser( $username, $reason, $time_banned );
           
        return Redirect( '?p=banlist' );
    }
?>
