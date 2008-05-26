<?php
	
	function ElementUserProfileView( tString $name , tString $subdomain ) {
		global $page;
		global $user;
		
		$name = $name->Get();
		$subdomain = $subdomain->Get();
		$finder = New UserFinder();
		if ( $name != '' ) {
			if ( strtolower( $name ) == strtolower( $user->Name ) ) {
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
			?>Ο χρήστης δεν υπάρχει<?php
		}
		$page->SetTitle( $theuser->Name );
		?><div id="profile"><?php
			Element( 'user/profile/sidebar/view' , $theuser );
			Element( 'user/profile/main/view' , $theuser );
			?><div class="eof"></div>
		</div><?php
	}
?>
