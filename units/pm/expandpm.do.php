<?php
function UnitPmExpandpm( tInteger $pmid ) {
	global $libs;
	global $user;
	
	$libs->Load( 'pm' );
	$pmid = $pmid->Get();
	$pm = new PM( $pmid );
	?>alert( <?php 
	echo $pm->Id;
	?> );<?php
	if ( !$pm->IsRead ) {	
		$pm->DelId = 1;	
		$pm->Save();
	}
}
?>