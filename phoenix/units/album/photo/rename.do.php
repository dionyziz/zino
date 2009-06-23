<?php
    function UnitAlbumPhotoRename( tInteger $photoid , tText $photoname ) {
        global $user;
        global $libs;
        
        $libs->Load( 'image/image' );
        
        $image = New Image( $photoid->Get() );
        if ( $image->Userid == $user->Id ) {
            $image->Name = $photoname->Get();
            $image->Save();
        }
    }
?>
