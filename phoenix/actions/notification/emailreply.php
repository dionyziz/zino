<?php
    function ActionNotificationEmailReply( tText $rawdata ) {
        global $libs;
        
        $libs->Load( 'notify/emailreplyhandler' );
        
        $rawdata = $rawdata->Get();
        Notify_ReplyParse( $rawdata );
    }
?>
