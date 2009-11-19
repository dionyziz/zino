<?php
    function UnitBackendSpotCommentCreated( Comment $comment ) {
        global $libs;
        global $rabbit_settings;
        
        $libs->Load( 'research/spot' );
        
        Spot::CommentCreated( $comment );

        return false;
    }
?>
