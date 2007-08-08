<?php
	function UnitChatSend( tString $message ) {
		global $user;
		global $libs;
		
        $message = $message->Get();
        
		$libs->Load( 'chat' );

        if ( $user->Exists() ) {
			AddChat( $message );
        }
	}
?>
