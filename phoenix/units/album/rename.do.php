<?php
    function UnitAlbumRename( tInteger $albumid , tText $albumname ) {
        global $user;
        global $libs;
        
        $libs->Load( 'album' );
        
        $album = New Album( $albumid->Get() );
        if ( $album->Ownertype == TYPE_USERPROFILE && $album->Ownerid == $user->Id ) {
            $album->Name = $albumname->Get();
            $album->Save();
        }
    }
?>
