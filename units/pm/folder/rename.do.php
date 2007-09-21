<?php
    function UnitFolderRename( tInteger $folderid, tString $newname ) {
        global $user;
        
        $folderid = $folderid->Get();
        $newname = $newname->Get();
        
        $folder = New PMFolder( $folderid );
        if ( !$folder->Exists() ) {
            return;
        }
        if ( $folder->UserId != $user->Id() ) {
            return;
        }
        $folder->Name = $newname;
        $folder->Save();
    }
?>
