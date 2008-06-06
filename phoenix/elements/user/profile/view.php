<?php
	
	function ElementUserProfileView( tString $name , tString $subdomain, tInteger $commentid , tInteger $offset ) {
		global $page;
		global $user;
		
		$commentid = $commentid->Get();
		$offset = $offset->Get();
		$name = $name->Get();
		$subdomain = $subdomain->Get();
		$finder = New UserFinder();

        ob_start();
        ?>alert( "testing --dionyziz" );<?php
        $page->AttachInlineScript( ob_get_clean() );

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
			return;
		}
		$page->SetTitle( $theuser->Name );
		?><div id="profile"><?php
			Element( 'user/profile/sidebar/view' , $theuser );
			Element( 'user/profile/main/view' , $theuser, $commentid, $offset );
			?><div class="eof"></div>
		</div><?php
	}
?>
