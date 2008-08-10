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
        if ( $user->Id != $tag->Ownerid ) {
            ?>alert( "Δεν μπορείς να διαγράψεις ένα Tag που δεν δημιούργησες ο ίδιος" );
            window.location.reload();<?php
            return;
        }
        $tag->Delete();
    }
?>
