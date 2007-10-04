<?php
    function UnitPmFolderRename( tInteger $folderid, tString $newname ) {
        global $user;
        global $libs;
		
		$libs->Load( 'pm' );
		
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
