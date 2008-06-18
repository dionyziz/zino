<?php
    function UnitPmExpandpm( tInteger $pmid, tInteger $folderid ) {
    	global $libs;
    	global $user;
    	global $water;
    	
    	$libs->Load( 'pm/pm' );
    	$pmid = $pmid->Get();
        $folderid = $folderid->Get();
        $water->Trace( 'pmid,folderid', $pmid, $folderid );
    	$pm = new PM( $pmid, $folderid );
    	if ( !$pm->IsRead() ) {	
            $pm->Read();
    	}
    }
?>
