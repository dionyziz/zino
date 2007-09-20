 <?php
	function ElementChatView() {
		global $user;
		global $water;
		global $page;
		global $libs;
		global $xc_settings;
		
		if ( !$xc_settings[ 'chat' ][ 'enabled' ] ) {
			return;
		}
        if ( !$user->Exists() ) {
            return Redirect( 'register' );
        }
		
		$page->SetTitle( 'Chat' );
		
		?>
		<br /><br /><br />
        <applet code="http://static.chit-chat.gr/chat/ice_queen/alpha/Frontend" width="700" height="400">
            <param name="userid" value="<?php
            echo $user->Id();
            ?>" />
            <param name="authtoken" value="<?php
            echo $user->Authtoken();
            ?>" />
            <param name="username" value="<?php
            echo $user->Username();
            ?>" />
            <b>Πρέπει να εγκαταστήσεις το Java Runtime Environment για να μπορείς να κάνεις chat.</b><br />
            <a href="http://www.java.com/getjava/">Μπορείς να το κάνεις πολύ εύκολα απλώς κατεβάζοντάς το</a>.
        </applet>
        <?php
	}
?>
