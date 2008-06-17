<?php

    function UnitPmMove( tInteger $pmid, tInteger $folderid, tInteger $targetfolderid ) {
        global $libs;
        
        $libs->Load( 'pm' );
        
        $pmid = $pmid->Get();
        $folderid = $folderid->Get();
        $targetfolderid = $targetfolderid->Get();

        $folder = New PMFolder( $targetfolderid );
        if ( !$folder->Exists() ) {
            return;
        }
        
        $pm = New UserPM( $pmid, $folderid );
        $pm->FolderId = $targetfolderid;
        $pm->Save();
    }

?>
