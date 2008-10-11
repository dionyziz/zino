<?php
    function UnitCommentsEdit( tInteger $id, tText $text ) {
        global $libs;
        global $user;
        
        $libs->Load( 'comment' );
        $libs->Load( 'wysiwyg' );
        
        $id = $id->Get();
        $text = $text->Get();
        $text = trim( $text );
        
        if ( $text == '' ) {
            ?>alert( "Δε μπορείς να δημοσιεύσεις κενό μήνυμα" );
            window.location.reload();<?php
            return;
        }
        
        $comment = New Comment( $id );
        if ( !$comment->Exists() ) {
            ?>alert( "Προσπαθείς να επεξεργαστείς το κείμενο ενός ανύπαρκτου σχολίου" );
            window.location.reload();<?php
            return;
        }
        if ( $user->Id != $comment->Userid && !$user->HasPermission( PERMISSION_COMMENT_EDIT_ALL ) ) {
            ?>alert( "Δεν έχεις δικαίωμα να επεξεργαστείς το συγκεκριμένο σχόλιο" );
            window.location.reload();<?php
            return;
        }
        if ( time()-strtotime( $comment->Created ) > 900 && !$user->HasPermission( PERMISSION_COMMENT_EDIT_ALL ) ) {
            ?>alert( "Έχει περάσει ένα τέταρτο από τη στιγμή που δημιουργήθηκε το σχόλιο αυτό" );
            window.location.reload();<?php
            return;
        }
        $text = nl2br( htmlspecialchars( $text ) );
        $comment->Text = WYSIWYG_PostProcess( $text );
        $comment->Save();

        Element::ClearFromCache( 'comment/list', $comment->Typeid, $comment->Itemid );

        ?>$( 'div#comment_<?php
        echo $id;
        ?> div.text' ).html( <?php
        echo w_json_encode( $comment->Text );
        ?> );<?php
    }    
?>
