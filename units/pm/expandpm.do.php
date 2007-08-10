<?php
function UnitPmExpandpm( tInteger $pmid ) {
	global $libs;
	global $user;
	
	$libs->Load( 'pm' );
	$pmid = $pmid->Get();

	$pm = new PM( $pmid );
	if ( $pm->Sender->Id() == $user->Id() && !$pm->IsRead ) {
		$pm->DelId = 1;		
	}
}
?>