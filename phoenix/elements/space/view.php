<?php
	
	function ElementSpaceView( tString $username , tString $subdomain ) {
		global $user;
		
		$username = $username->Get();
		$subdomain = $subdomain->Get();
		$finder = New UserFinder();
		if ( $username != '' ) {
			if ( strtolower( $username ) == strtolower( $user->Name ) ) {
				$theuser = $user;
			}
			else {
				$theuser = $finder->FindByName( $username );
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
			?>Ο χρήστης δεν υπάρχει<?php
			return;
		}	
		Element( 'user/sections' , 'space' , $theuser );
		?><div id="space">
		<h2>Χώρος</h2>
		αδφιοασοδιφξασιοδξφοιασδξφοι
		
		</div><?php
	}
?>
