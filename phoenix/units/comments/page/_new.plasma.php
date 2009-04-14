<?php
    function UnitCommentsPageNew( Comment $comment ) {
        ?>alert( "commentid is: <?php
        echo $comment->Id;
        ?>" );<?php



        
    }
?>
