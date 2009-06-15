<?php
    function UnitBackendSpotCommentCreated( Comment $comment ) {
        global $libs;
        global $rabbit_settings;
        
        if ( $rabbit_settings[ 'production' ] ) {
            return;
        }

        $libs->Load( 'research/spot' );
        
        Spot::CommentCreated( $comment );

        return false;
    }
?>
