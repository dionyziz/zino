<?php
    function UnitFrontpageCommentNew( Comment $comment ) {
        ?>var newdiv = document.createElement( 'div' );
        $( newdiv ).html( <?php
        ob_start();
        Element( 'frontpage/comment/view' , $comment );
        echo w_json_encode( ob_get_clean() );
        ?> );
        $( 'div.latest div.comments div.list' ).prepend( newdiv );
        var height = newdiv.offsetHeight;
        $( newdiv ).css( {
            'height': "0",
            'opacity': "0"
        } ).animate( {
            height: height,
            opacity: "1"
        } , 500 , 'linear' );
        $( 'div.latest div.comments div.list:last-child' ).animate( {
            height: "0",
            opacity: "0"
        } , 500 , 'linear' );<?php
    }
?>
