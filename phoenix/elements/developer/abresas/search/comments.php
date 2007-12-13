<?php

    function DisplayChildren( $comments, $parent ) {
        if ( !isset( $comments[ $parent ] ) ) {
            return;
        }
        $children = $comments[ $parent ];

        foreach ( $children as $comment ) {
            ?>[ <?php
            echo $comment->Id;
            ?> <?php
            echo $comment->User->Username();
            ?> <?php
            echo $comment->Since;
            ?> ]<br /><?php

            DisplayChildren( $comments, $comment->Id );
        }
    }

    function ElementDeveloperAbresasSearchComments( tInteger $userid ) {
        global $libs;
        global $user;

        $libs->Load( 'comment' );

        $comments = new CommentsSearch;
        $comments->TypeId   = 1;
        $comments->ItemId   = $userid->Get();
        $comments->DelId    = 0;

        $comments->OrderBy  = array( 'Created', 'DESC' );

        if ( $oldcomments ) {
            $comments->Limit = 10000;
        }
        else {
            $comments->Limit = 50;
        }

        $comments = $comments->GetParented();
        if ( empty( $comments ) ) {
            die( "No comments for user " . $userid->Get() . "!" );
        }

        DisplayChildren( $comments, 0 );
    }

?>
