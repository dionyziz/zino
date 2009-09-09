<?php
    function UnitPmFolderDelete( tInteger $folderid ) {
        global $user;
        global $libs;

        $libs->Load( 'pm/pm' );
        $folderid = $folderid->Get();
        $folder = New PMFolder( $folderid );
        if ( $folder->Userid != $user->Id ) {
            return;
        }
        $folder->Delete();
    }
?>
