<?php
	function UnitChatSend( tString $message ) {
		global $user;
		global $libs;
		
        if ( !$user->Exists() ) {
            return;
        }
        
        $libs->Load( 'callisto/callisto' );

        $message = $message->Get();
        $channel = New Callisto_Channel( '/chat/channels/kamibu' );
        $channel->Publish( '<' . $user->Username() . '> ' . $message );
    }
?>
