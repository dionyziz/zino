<?php
	function UnitPmTransfer( tInteger $pmid , tInteger $folderid ) {
		global $libs;
		global $water;
		global $user;
		
		$libs->Load( 'pm' );
		
		$pmid = $pmid->Get();
		$folderid = $folderid->Get();
		
		$pm = new PM( $pmid );
		$pm->FolderId = $folderid;
		$pm->Save();
	}
?>