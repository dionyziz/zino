<?php
	
	function ElementFrontpageShoutboxList() {
		global $user;
		global $libs;
		$libs->Load( 'shoutbox' );
		
		$finder = New ShoutboxFinder();
		$shouts = $finder->FindLatest( 0 , 7 )
		?><div class="shoutbox">
			<h2>Συζήτηση (<a href="?p=shoutbox">Αρχείο</a>)</h2>
			<div class="comments"><?php
				if ( $user->Exists() && $user->HasPermission( PERMISSION_SHOUTBOX_CREATE ) ) {
					Element( 'shoutbox/reply' );
				}
				foreach ( $shouts as $shout ) {
					Element( 'shoutbox/view' , $shout , false );
				}
				Element( 'shoutbox/view'  , false , true );
			?></div>
		</div><?php
	}
?>
