<?php
function UnitPmDeletepm( tInteger $pmid ) {
	global $user;
	global $libs;
	
	$libs->Load( 'pm' );
	$pm = new PM( $pmid->Get() );
	
	$pm->Delete();
	?>alert( 'deleted' );<?php
}
?>