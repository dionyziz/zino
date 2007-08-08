<?php
	function UnitChatMessages( tInteger $lastid ) {
		global $libs;
		global $user;
		global $xc_settings;
		
        $lastid = $lastid->Get();
        
		if ( !$xc_settings[ 'chatavailable' ] ) {
			?>window.location.href = 'index.php';<?php
			return;
		}
		
		$libs->Load( 'chat' );

        if ( !$user->Exists() ) {
            return false;
        }
        
		$msgs = getNewChatMessages( $lastid );
		foreach ( $msgs as $i => $message ) {
			$msgs[ $i ][ 'chat_message' ] = mformatshouts( array( $msgs[ $i ][ 'chat_message' ] ) );
		}
		?>Chat.Append(<?php
		echo w_json_encode( $msgs, -1, 0, true );
		?>);<?php
	}
?>
