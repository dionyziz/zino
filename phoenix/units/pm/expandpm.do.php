<?php
    function UnitPmExpandpm( tInteger $pmid, tInteger $folderid ) {
    	global $libs;
    	global $user;
    	
    	$libs->Load( 'pm/pm' );
    	$pmid = $pmid->Get();
        $folderid = $folderid->Get();
    	$pm = new PM( $pmid, $folderid );
    	if ( !$pm->IsRead() ) {	
            $pm->Read();
    	}
    }
?>
