<?php
	
	function ElementPollList( tString $username , tString $subdomain , tInteger $offset ) {
		global $libs;
		global $page;
		global $rabbit_settings;
		global $user;
		
		$libs->Load( 'poll/poll' );
		$username = $username->Get();
		
		$offset = $offset->Get();
		if ( $offset <= 0 ) {
			$offset = 1;
		}
		//$subdomain = $subdomain->Get();
		$finder = New UserFinder();
		if ( $name != '' ) {
			if ( strtolower( $name ) == strtolower( $subdomain ) ) {
				$theuser = $user;
			}
			else {
				$theuser = $finder->FindByName( $name );
			}
		}
		else if ( $subdomain != '' ) {
			if ( strtolower( $subdomain ) == strtolower( $user->Subdomain ) ) {
				$theuser = $user;
			}
			else {
				$theuser = $finder->FindBySubdomain( $subdomain );
			}
		}
		if ( !isset( $theuser ) || $theuser === false ) {
			return Element( '404' );
		}		
		
		if ( strtoupper( substr( $username, 0, 1 ) ) == substr( $username, 0, 1 ) ) {
			$page->SetTitle( $username . " Δημοσκοπήσεις" );
		}
		else {
			$page->SetTitle( $username . " δημοσκοπήσεις" );
		}

		$finder = New PollFinder();
		$polls = $finder->FindByUser( $theuser  , ( $offset - 1 )*5 , 5 );

		Element( 'user/sections', 'poll' , $theuser );
		?><div id="polllist">
			<ul><?php
				if ( $theuser->Id == $user->Id && $user->HasPermission( PERMISSION_POLL_CREATE ) ) {
					?><li class="create">
						<a href="" class="new"><img src="<?php
						echo $rabbit_settings[ 'imagesurl' ];
						?>add3.png" alt="Δημιουργία δημοσκόπησης" title="Δημιουργία δημοσκόπησης" />Δημιουργία δημοσκόπησης</a>
					</li><?php
				}
				if ( !empty( $polls ) ) {
					foreach ( $polls as $poll ) {
						?><li><?php
						Element( 'poll/small' , $poll , true );
						?></li><?php
					}
				}
				else {
					if ( $theuser->Id != $user->Id ) {
						?>Δεν υπάρχουν δημοσκοπήσεις<?php
					}
				}
			?></ul><?php
			if ( $theuser->Id == $user->Id && $user->HasPermission( PERMISSION_POLL_CREATE ) ) {
				?><div class="creationmockup">
					<div>
						<input type="text" /><a href=""><img src="<?php
						echo $rabbit_settings[ 'imagesurl' ];
						?>accept.png" alt="Δημιουργία" title="Δημιουργία" /></a>
					</div>
					<div class="tip">
						Γράψε μια ερώτηση για τη δημοσκόπησή σου
					</div>
				</div>
				<div class="tip2">
					Γράψε μια επιλογή για τη δημοσκόπησή σου
				</div>
				<div class="creatingpoll">
					<img src="<?php
					echo $rabbit_settings[ 'imagesurl' ];
					?>ajax-loader.gif" alt="Δημιουργία" title="Δημιουργία" /> Δημιουργία...
				</div><?php
			}
			?><div class="pagifypolls"><?php
			Element( 'pagify' , $offset , 'polls&username=' . $theuser->Subdomain , $theuser->Count->Polls , 5 , 'offset' );
			?></div>
			<div class="eof"></div>
		</div><?php
	}
?>
