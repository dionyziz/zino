<?php
	
	function ElementUserRelationsList( tString $username , tString $subdomain , tInteger $offset ) {
		global $libs;
		global $user;
		global $page;
		
		$libs->Load( 'relation/relation' );
		
		$username = $username->Get();
		$subdomain = $subdomain->Get();
		
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
		
		$offset = $offset->Get();
		if ( $offset <= 0 ) {
			$offset = 1;
		}
		if ( strtoupper( substr( $theuser->Name, 0, 1 ) ) == substr( $theuser->Name, 0, 1 ) ) {
			$page->SetTitle( $theuser->Name . " Φίλοι" );
		}
		else {
			$page->SetTitle( $theuser->Name . " φίλοι" );
		}
		
		$finder = New UserFinder();
		$friends = $finder->FindByUser( $theuser , ( $offset - 1 )*20 , 20 );
		Element( 'user/sections', 'relations' , $theuser );
		?><div id="relations">
			<h3>Φίλοι</h3><?php
			Element( 'user/list' , $friends );
			?><div class="pagifyrelations"><?php
			Element( 'pagify' , $offset , 'friends&subdomain=' . $theuser->Subdomain , $theuser->Count->Relations , 20 , 'offset' );
			?></div>
			<div class="eof"></div>
		</div><?php
	
	}
?>
