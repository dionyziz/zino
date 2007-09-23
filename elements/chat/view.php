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
		<br /><br /><br /><br />
        <div style="text-align:center">
            <iframe src="<?php
            echo $xc_settings[ 'chat' ][ 'applet' ];
            ?>?userid=<?php
            echo $user->Id();
            ?>&username=<?php
            echo $user->Username();
            ?>&authtoken=<?php
            echo $user->Authtoken();
            ?>" width="750" height="550"></iframe>
        </div>
        <?php
	}
?>
