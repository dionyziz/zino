<?php
    function UnitCommentsPageNew( Comment $comment ) {
        ?>var node = $( <?php
        ob_start();
        Element( 'comment/view' , $comment , 0 , 0 );
        echo w_json_encode( ob_get_clean() );
        ?> );
        if ( <?php
        echo $comment->User->Name;
        ?> == GetUsername() ) {
            alert( 'same user' );
            return;
        }
        if ( Comments.typing ) {
            Comments.Page.NodeQueue.unshift( node );
            Comments.Page.ParentidQueue.unshift( '<?php
            echo $comment->Parentid;
            ?>' );
        }
        else {
            Comments.Page.ShowComment( node , '<?php
            echo $comment->Parentid;
            ?>' , 1000 );
        }<?php

        return $comment->Typeid.'c'.$comment->Itemid;
    }
?>
