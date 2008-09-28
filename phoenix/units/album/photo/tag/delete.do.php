<?php
    function UnitAlbumPhotoTagDelete( tInteger $id ) {
        global $user;
        global $libs;
        
        $libs->Load( 'relation/relation' );
        $libs->Load( 'image/tag' );
        
        if ( !$user->Exists() ) {
            ?>alert( "Πρέπει να είσαι συνδεδεμένος για να διαγράψεις ένα Tag" );
            window.location.reload();<?php
            return;
        }
        
        $tag = New ImageTag( $id );
        $img = New Image( $tag->Imageid );
        if ( $user->Id != $tag->Ownerid && $user->Id != $img->User->Id ) {
            ?>alert( "Δεν μπορείς να διαγράψεις ένα Tag που δεν δημιούργησες ο ίδιος ή δεν έχει γίνει σε δικιά σου εικόνα" );
            window.location.reload();<?php
            return;
        }
        $tag->Delete();
    }
?>
