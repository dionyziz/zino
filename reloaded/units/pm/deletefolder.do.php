<?php
    function UnitPmDeletefolder( tInteger $folderid ) {
        global $user;
        global $libs;

        $libs->Load( 'pm' );
        $folderid = $folderid->Get();
        $folder = new PMFolder( $folderid );
        if ( $folder->UserId != $user->Id() ) {
            return;
        }
        $folder->Delete();
    }
?>