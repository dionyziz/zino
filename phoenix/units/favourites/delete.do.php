<?php
    function UnitFavouritesDelete( tInteger $favid ) {
        global $libs;
        global $user;
        
        if ( !$user->Exists() ) {
            return;
        }
        
        $libs->Load( 'favourite' );
        
        $favourite = New Favourite( $favid );
        
        if ( $favourite->Exists() ) {
            ?>alert( "Favourite does not exists" );<?php
            return;
        }
        if ( $favourite->Userid == $user->Id ) {
            ?>alert( "You cannot delete a favourite you do not own" );<?php
            return;
        }
        
        $favourite->Delete();
        
        ?>$( 'div#pview div.image_tags:last' ).html( '<?php
        Element( 'album/photo/favouritedby', $itemid->Get(), -1 );
        ?> '); <?php
    }
?>
