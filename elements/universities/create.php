<?php
	function ElementUniversitiesCreate() {
		global $user;
		global $water;
		global $libs;
		global $page;
		
		$page->SetTitle( 'Διαχείριση πανεπιστημίων' );
		$libs->Load( 'universities' );
		
		if ( !$user->CanModifyCategories() ) {
			die( 'Δεν έχεις δικαίωμα να δεις αυτή τη σελίδα' );
		}
		$allunis = Uni_Retrieve( 0 , false );
		$water->Trace( 'uni number: ' . count( $allunis ) );
		?><br /><br /><h3>Διαχείριση πανεπιστημίων</h3><?php
		foreach( $allunis as $uni ) {
			echo htmlspecialchars( $uni->Name );
			?><br /><?php
		}
		
		
	}
?>