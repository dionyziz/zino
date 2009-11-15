<?php
    function UnitBackendSpotVoteCreated( Vote $vote ) {
        global $libs;
        global $rabbit_settings;
        
        if ( $rabbit_settings[ 'production' ] ) {
            return;
        }

        $libs->Load( 'research/spot' );
        
        Spot::VoteCreated( $vote );

        return false;
    }
?>
