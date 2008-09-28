<?php

	function UnitPmDelete( tInteger $pmid, tInteger $folderid ) {
		global $user;
		global $libs;
		
		$libs->Load( 'pm/pm' );
		$pm = new UserPM( $pmid->Get(), $folderid->Get() );

		$pm->Delete();
	}

?>
