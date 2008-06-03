<?php
	
	function ElementFrontpageShoutboxList() {
		global $user;
		global $libs;
		$libs->Load( 'shoutbox' );
		
		$finder = New ShoutboxFinder();
		$shouts = $finder->FindLatest( 0, 8 )
		?><div class="shoutbox">
			<h2>Συζήτηση</h2>
			<div class="comments"><?php
				if ( $user->Exists() && $user->HasPermission( PERMISSION_SHOUTBOX_CREATE ) ) {
					Element( 'frontpage/shoutbox/reply' );
				}
				foreach ( $shouts as $shout ) {
					Element( 'frontpage/shoutbox/view' , $shout , false );
				}
				Element( 'frontpage/shoutbox/view'  , false , true );
			?></div>
		</div><?php
	}
?>