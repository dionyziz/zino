<?php
    function UnitFrontpageCommentNew( Comment $comment ) {
        ?>var newdiv = document.createElement( 'div' );
        $( newdiv ).html( <?php
        ob_start();
        Element( 'frontpage/comment/view' , $comment );
        echo w_json_encode( ob_get_clean() );
        ?> );
        $( 'div.latest div.comments div.list' ).prepend( newdiv );
        alert( 'comment created' );<?php
    }
?>
