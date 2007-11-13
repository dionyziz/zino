<?php
    function ElementChatCallisto() {
        global $user;
        global $libs;
        
        if ( !$user->Exists() ) {
            return;
        }
        
        $libs->Load( 'callisto/callisto' );
        
        $channel = New Callisto_Channel( 'chat/channels/kamibu' );
        $channel->Subscribe();
    }
?>
