<?php
    function UnitPmMove( tInteger $pmid, tInteger $targetfolderid ) {
        global $libs;
        
        $libs->Load( 'pm' );
        
        $pmid = $pmid->Get();
        $targetfolderid = $targetfolderid->Get();
        $folder = New PMFolder( $targetfolderid );
        if ( !$folder->Exists() ) {
            return;
        }
        
        $pm = New PM( $pmid );
        $pm->FolderId = $targetfolderid;
        $pm->Save();
    }
?>
