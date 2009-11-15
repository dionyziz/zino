<?php
    function UnitBackendSpotFavouriteCreated( Favourite $favourite ) {
        global $libs;
        global $rabbit_settings;
        
        if ( $rabbit_settings[ 'production' ] ) {
            return;
        }

        $libs->Load( 'research/spot' );
        
        Spot::FavouriteCreated( $favourite );

        return false;
    }
?>
