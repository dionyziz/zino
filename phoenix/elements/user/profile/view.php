<?php
	function ElementUserProfileView( tString $name , tString $subdomain ) {
		global $page;
		
		$name = $name->Get();
		$subdomain = $subdomain->Get();
		$finder = New UserFinder();
		if ( $name != '' ) {
			$theuser = $finder->FindByName( $name );
		}
		else {
			$theuser = $finder->FindBySubdomain( $subdomain );
		}
		if ( $theuser === false ) {
			return Element( '404' );
		}
		
		$page->AttachStyleSheet( 'css/user/profile/view.css' );
		
		?><div id="profile"><?php
			Element( 'user/profile/sidebar/view' );
			Element( 'user/profile/main' );
			?><div class="eof"></div>
		</div><?php
	}
?>