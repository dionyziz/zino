<?php
    function UnitAlbumsDelete( tInteger $albumid ) {
        global $libs;
        global $user;
        
        $albumid = $albumid->Get();
        $libs->Load( 'albums' );
        $thisalbum = New Album( $albumid ); 
        if ( $thisalbum->UserId() == $user->Id() || $user->CanModifyCategories() ) {
            $thisalbum->Delete();
        }
    }
?>
