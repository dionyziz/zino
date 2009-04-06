<?php
    function UnitFrontpageCommentNew( Comment $comment ) {
        ?>var newdiv = $( <?php
        ob_start();
        Element( 'frontpage/comment/view' , $comment );
        echo w_json_encode( ob_get_clean() );
        ?> );
        if ( Frontpage.Comment.Animating || Frontpage.Comment.MouseOver ) {
            Frontpage.Comment.Queue.unshift( newdiv );
        }
        else {
            Frontpage.Comment.ShowComment( newdiv );
        }<?php
    }
?>
