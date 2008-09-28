<?php
	function UnitPmExpand( tInteger $pmid, tInteger $folderid ) {
		global $libs;
		global $user;
		global $water;
		
		$libs->Load( 'pm/pm' );
		$pmid = $pmid->Get();
		$folderid = $folderid->Get();
		$pm = new UserPM( $pmid, $folderid );
		if ( !$pm->IsRead() ) {	
			$pm->Read();
		}
	}
?>
