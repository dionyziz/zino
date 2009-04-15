<?php
    function UnitCommentsPageNew( Comment $comment ) {
        ?>var node = $( <?php
        ob_start();
        Element( 'comment/view' , $comment );
        echo w_json_encode( ob_get_clean() );
        ?> );
        Comments.Page.ShowComment( node , '<?php
        echo $comment->Parentid;
        ?>' );<?php

        return $comment->Typeid.'c'.$comment->Itemid;
    }
?>
