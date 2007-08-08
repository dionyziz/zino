<?php
	function UnitChatUsers() {
		global $libs;
		global $user;
		
		if ( !$user->Exists() ) {
			return;
		}
		
        $libs->Load( 'chat' );
		$user->UpdateInChat();
		
		?>Chat.AppendUsers( <?php
		echo w_json_encode( UsersInChat() );
		?> );<?php
	}
?>
