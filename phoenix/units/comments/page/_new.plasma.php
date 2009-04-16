<?php
    function UnitCommentsPageNew( Comment $comment ) {
        ?>var comnode = $( <?php
        ob_start();
        Element( 'comment/view' , $comment , 0 , 0 );
        echo w_json_encode( ob_get_clean() );
        ?> );
        <?php
            /*var comobj = {
            node : comnode,
            parentid : '<?php
            echo $comment->Parentid;
            ?>',
            name : '<?php
            echo $comment->User->Name;
            ?>',
            type : '<?php
            echo $comment->Typeid;
            ?>'
        };
        */
        ?>if ( Comments.typing ) {
            Comments.Page.Queue.unshift( comobj );
        }
        else {
            Comments.Page.ShowComment( comnode , '<?php
            echo $comment->Parentid;
            ?>' , '<?php
            echo $comment->User->Name;
            ?>' , '<?php
            echo $comment->Typeid;
            ?>' , 1000 );
        }<?php

        return $comment->Typeid.'c'.$comment->Itemid;
    }
?>
