<?php
    function UnitBackendSpotVoteCreated( Vote $vote ) {
        global $libs;
        global $rabbit_settings;
        
        $libs->Load( 'research/spot' );
        
        Spot::VoteCreated( $vote );

        return false;
    }
?>
