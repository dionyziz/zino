<?php
	
	function ElementPollList( tString $username ) {
		global $page;
		global $user;
		global $water;
		global $libs;
		global $rabbit_settings;
		
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
		?><div id="polllist">
			<ul><?php
				foreach ( $polls as $poll ) {
					?><li><?php
					Element( 'poll/small' , $poll , true );
					?></li><?php
				}
				if ( $theuser->Id == $user->Id ) {
					?><li class="create">
						<a href="" class="new"><img src="<?php
						echo $rabbit_settings[ 'imagesurl' ];
						?>add3.png" alt="Δημιουργία δημοσκόπησης" title="Δημιουργία δημοσκόπησης" />Δημιουργία δημοσκόπησης</a>
					</li><?php
				}
			?></ul><?php
			if ( $theuser->Id == $user->Id ) {
				?><div class="creationmockup">
					<input type="text" /><a href=""><img src="<?php
					echo $rabbit_settings[ 'imagesurl' ];
					?>accept.png" alt="Δημιουργία" title="Δημιουργία" /></a>
					<a href=""><img src="<?php
					echo $rabbit_settings[ 'imagesurl' ];
					?>cancel.png" alt="Ακύρωση" title="Ακύρωση" /></a>
					<span class="tip">
						<img src="<?php
						echo $rabbit_settings[ 'error' ];
						?>error.png" alt="Προσοχή" title="Προσοχή" /> Γράψε μια ερώτηση για τη δημοσκόπησή σου
					</span>
				</div><?php
			}
		?></div><?php
	}
?>
