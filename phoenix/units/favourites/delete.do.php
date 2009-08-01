<?php
    function UnitFavouritesDelete( tInteger $favid ) {
        global $libs;
        global $user;
        
        if ( !$user->Exists() ) {
            return;
        }
        
        $libs->Load( 'favourite' );
        
        $favid = $favid->Get();
        
        $favourite = New Favourite( $favid );
        
        if ( !$favourite->Exists() ) {
            ?>alert( "Favourite with id:<?php echo $favourite->Id; ?> does not exists" );<?php
            return;
        }
        if ( $favourite->Userid != $user->Id ) {
            ?>alert( "You cannot delete a favourite you do not own" );<?php
            return;
        }
        
        $favourite->Delete();
    }
?>
