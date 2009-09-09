<?php

    function UnitPmDelete( tInteger $pmid, tInteger $folderid ) {
        global $user;
        global $libs;
        
        $libs->Load( 'pm/pm' );
        $pm = New UserPM( $pmid->Get(), $folderid->Get() );

        $pm->Delete();
    }

?>
