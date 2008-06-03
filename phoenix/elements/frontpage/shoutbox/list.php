<?php
	
	function ElementFrontpageShoutboxList() {
		global $user;
		global $libs;
		$libs->Load( 'shoutbox' );
		
		$finder = New ShoutFinder();
		$shouts = $finder->FindLatest( 0, 5 )
		?><div class="shoutbox">
			<h2>Συζήτηση</h2>
			<div class="comments"><?php
				if ( $user->Exists() ) {
					Element( 'frontpage/shoutbox/reply' );
				}
				foreach ( $shouts as $shout ) {
					Element( 'frontpage/shoutbox/view' , $shout );
				}
			?></div>
		</div><?php
	}
?>