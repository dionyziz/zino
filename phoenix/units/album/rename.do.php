<?php

    function UnitAlbumRename( tInteger $albumid , tText $albumname ) {
        global $user;
        
        $album = New Album( $albumid->Get() );
        if ( $album->Ownertype == TYPE_USERPROFILE && $album->Owner->Id == $user->Id ) {
            $album->Name = $albumname->Get();
            $album->Save();
        }
    }
?>
