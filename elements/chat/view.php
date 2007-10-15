<?php
	function ElementChatView() {
		global $user;
		global $water;
		global $page;
		global $libs;
		global $xc_settings;
		global $rabbit_settings;
        
		if ( $user->Rights() < $xc_settings[ 'chat' ][ 'enabled' ] ) {
			return;
		}
        if ( !$user->Exists() ) {
            return Redirect( 'register' );
        }
		
		$page->SetTitle( 'Chat' );
		$page->SetBase( $rabbit_settings[ 'webaddress' ] . '/chat/' );
        
		?><br /><br /><br /><br />
        <div style="text-align:center">
            <applet code="Frontend" width="700" height="400">
                <param name="userid" value="<?php
                echo $user->Id();
                ?>" />
                <param name="username" value="<?php
                echo $user->Username();
                ?>" />
                <param name="authtoken" value="<?php
                echo $user->Authtoken();
                ?>" />
                <b>You must have Java Runtime Environment installed on this application</b>
            </applet>
        </div><?php
        
        return array( 'tiny' => true );
	}
?>
