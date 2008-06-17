<?php

    function UnitPmExpandpm( tInteger $pmid, tInteger $folderid ) {
    	global $libs;
    	$libs->Load( 'pm/pm' );

    	$pmid = $pmid->Get();
        $folderid = $folderid->Get();

    	$pm = new UserPM( $pmid, $folderid );
    	if ( !$pm->IsRead() ) {	
            $pm->Read();
    	}
    }

?>
