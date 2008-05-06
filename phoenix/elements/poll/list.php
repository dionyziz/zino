<?php
	
	function ElementPollList( tString $username ) {
		global $page;
		global $user;
		global $water;
		global $libs;
		
		$libs->Load( 'poll/poll' );
		$username = $username->Get();
		//$subdomain = $subdomain->Get();
		$finder = New UserFinder();
		if ( $username != '' ) {
			$theuser = $finder->FindByName( $username );
			if ( strtoupper( substr( $username, 0, 1 ) ) == substr( $username, 0, 1 ) ) {
				$page->SetTitle( $username . " Δημοσκοπήσεις" );
			}
			else {
				$page->SetTitle( $username . " δημοσκοπήσεις" );
			}
		}
		if ( !isset( $theuser ) || $theuser === false ) {
			return Element( '404' );
		}
		$finder = New PollFinder();
		$polls = $finder->FindByUser( $theuser );
		$water->Trace( 'poll number: ' . count( $polls ) );
		Element( 'user/sections', 'album' , $theuser );
		?><div id="journallist">
			<ul><?php
				foreach ( $polls as $poll ) {
					?><li><?php
					Element( 'poll/small' , $poll , true );
					?></li><?php
				}
			?></ul>
			
		</div><?php
	}
?>
