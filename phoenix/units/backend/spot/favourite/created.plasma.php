<?php
    function UnitBackendSpotFavouriteCreated( Favourite $favourite ) {
        global $libs;
        global $rabbit_settings;
        
        $libs->Load( 'research/spot' );
        
        Spot::FavouriteCreated( $favourite );

        return false;
    }
?>
