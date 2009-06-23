<?php
    function UnitAlbumPhotoRename( tInteger $photoid , tText $photoname ) {
        global $user;
        global $libs;
        
        $libs->Load( 'image/image' );
        
        $image = New Image( $photoid->Get() );
        if ( $image->User->Id == $user->Id ) {
            $image->Name = $photoname->Get();
            $image->Save();
        }
    }
?>
