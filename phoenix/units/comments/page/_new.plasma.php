<?php
    function UnitCommentsPageNew( Comment $comment ) {
        die( "teeest" );
        ?>alert( "commentid is: <?php
        echo $comment->Id;
        ?>" );<?php

        return $comment->Typeid.'c'.$comment->Itemid;
    }
?>
