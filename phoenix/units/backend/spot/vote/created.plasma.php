<?php
    function UnitBackendSpotVoteCreated( PollVote $vote ) {
        global $libs;
        global $rabbit_settings;
        
        $libs->Load( 'research/spot' );
        
        Spot::VoteCreated( $vote );

        return false;
    }
?>
