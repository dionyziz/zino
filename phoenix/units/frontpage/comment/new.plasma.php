<?php
    function UnitFrontpageCommentNew( Comment $comment ) {
        ?>var newdiv = document.createElement( 'div' );
        $( newdiv ).html( <?php
        ob_start();
        Element( 'frontpage/comment/view' , $comment );
        echo w_json_encode( ob_get_clean() );
        ?> );
        $( 'div.latest div.comments div.list' ).prepend( newdiv );
        var targetheight = newdiv.offsetHeight;
        alert( targetheight );
        //$( newdiv ).show( 1200 );
        newdiv.style.height = '0';
        $( newdiv ).css( 'opacity' , '0' ).animate( {
            height: targetheight,
            opacity: "1"
        } , 500 , 'linear' );
        $( 'div.latest div.comments div.list>div:last-child' ).animate( {
            height: "0",
            opacity: "0"
        } , 500 , 'linear' , function() {
            $( this ).remove();
        } );<?php
    }
?>
