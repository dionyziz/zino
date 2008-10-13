<?php
    function UnitCommentsDelete( tInteger $commentid, tCoalaPointer $callback ) {
        global $user;
        global $libs;
        
        $commentid = $commentid->Get();
        
        $libs->Load( 'comment' );
        
        $comment = New Comment( $commentid );
        if ( !$comment->Exists() ) {
            ?>alert( 'Το σχόλιο που προσπαθήτε να διαγράψετε δεν υπάρχει' );
            window.location.reload();<?php
            return;
        }
        if ( $comment->IsDeleted() ) {
            ?>alert( 'To σχόλιο που προσπαθήτε να διαγράψετε έχει ήδη διαγραφεί' );
            window.location.reload();<?php
            return;
        }
        if ( $user->Id != $comment->Userid && !$user->HasPermission( PERMISSION_COMMENT_DELETE_ALL ) ) {
            ?>alert( 'Δεν έχετε δικαίωμα να διαγράψετε το συγκεκριμένο σχόλιο' );
            window.location.reload();<?php
            return;
        }
        $finder = New CommentFinder();
        if ( $finder->CommentHasChildren( $comment ) ) { // TODO: this check can and HAS failed under race conditions; make it atomic
            ?>alert( 'Το σχόλιο που προσπαθήτε να διαγράψετε έχει απαντήσεις' );
            window.location.reload();<?php
            return;
        }
        Element::ClearFromCache( 'comment/list', $comment->Typeid, $comment->Itemid );

        $parent = $comment->Parent;
        $comment->Delete();
        
        echo $callback;
        ?>( <?php
        echo $commentid;
        ?>, <?php
        if ( empty( $parent->Id ) ) {
            ?>0<?php
        }
        else {
            echo $parent->Id;
        }
        ?>, <?php
        if ( $parent->Userid == $user->Id || $user->HasPermission( PERMISSION_COMMENT_DELETE_ALL ) ) {
            ?>true<?php
        }
        else {
            ?>false<?php
        }
        ?> );<?php
    }    
?>
