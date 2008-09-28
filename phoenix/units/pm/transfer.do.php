<?php
	function UnitPmTransfer( tInteger $pmid, tInteger $folderid, tInteger $targetfolderid ) {
		global $libs;
		global $water;
		global $user;
		
		$libs->Load( 'pm/pm' );
		
		$pmid = $pmid->Get();
		$folderid = $folderid->Get();
		$targetfolderid = $targetfolderid->Get();

		$pm = new UserPM( $pmid, $folderid );
		w_assert( $pm->Exists() );
		$pm->Folderid = $targetfolderid;
		$pm->Save();
	}
?>
