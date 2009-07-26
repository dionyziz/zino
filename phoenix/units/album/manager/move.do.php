<?php
    function UnitAlbumManagerMove( tInteger $photoid, tInteger $albumid ) {
        global $libs;
        global $user;
        
        $libs->Load( 'album' );
    
        $album = New Album( $albumid->Get() );
        $photo = New Photo( $photoid->Get() );
        
        if ( !$album->Exists() || !$photo->Exists() ) {
            return;
        }
        
        if ( $album->Ownerid == $user->Id && $photo->Ownerid == $user->Id ) {
            $photo->MoveTo( $album );
        }
    }
?>