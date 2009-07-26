<?php
    function UnitAlbumManagerMove( tInteger $photoid, tInteger $albumid ) {
        global $libs;
        global $user;
        
        $libs->Load( 'album' );
    
        $album = New Album( $albumid->Get() );
        $photo = New Image( $photoid->Get() );
        
        if ( !$album->Exists() || !$photo->Exists() ) {
            ?>alert( "Album or image does not exist" );<?php
            return;
        }
        
        if ( $album->Ownerid == $user->Id && $photo->Userid == $user->Id ) {
            $photo->MoveTo( $album );
            ?>alert( "Moved" );<?php
        }
        else {
            ?>alert( "You do not own either the Album or the Photo" );<?php
        }
    }
?>