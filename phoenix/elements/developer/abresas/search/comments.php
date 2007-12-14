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

        /*
        $comments = new CommentsSearch;
        $comments->TypeId   = 1;
        $comments->ItemId   = $userid->Get();
        $comments->DelId    = 0;

        $comments->SortBy       = 'Created';
        $comments->SortOrder    = 'DESC';

        if ( $oldcomments ) {
            $comments->Limit = 10000;
        }
        else {
            $comments->Limit = 50;
        }
        */

        $comment = New CommentPrototype();
        $comment->TypeId = 1;
        $comment->ItemId = $userid->Get();
        $comment->DelId = 0;

        $user = New UserPrototype();
        $user->DelId = 0;

        $image = New ImagePrototype();

        $search = new Search();
        $search->AddPrototype( $comment );
        $search->AddPrototype( $user );
        $search->AddPrototype( $image );

        $search->Connect( $comment, $user );
        $search->Connect( $user, $image, $connection = 'left' );

        $search->SetSortMethod( $comment, 'Created', 'DESC' );
        $search->SetGroupBy( $user, 'Id' );

        $search->Limit = 20;

        $comments = $search->Get( $comment );

        var_dump( $comments );
    
        /*
        $comments = $comments->GetParented();
        if ( empty( $comments ) ) {
            die( "No comments for user " . $userid->Get() . "!" );
        }

        DisplayChildren( $comments, 0 );
        */
    }

?>
