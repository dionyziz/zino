<?php
    function UnitBackendSpotCommentCreated( Comment $comment ) {
        global $libs;
        
        $libs->Load( 'research/spot' );
        
        Spot::CommentCreated( $comment );

        return false;
    }
?>
