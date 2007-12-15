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
        $libs->Load( 'prototype/comment' );
        $libs->Load( 'prototype/user' );
        $libs->Load( 'prototype/image' );

        $comment = New CommentPrototype();
        $comment->TypeId = 1;
        $comment->ItemId = $userid->Get();
        $comment->DelId = 0;

        $user = New UserPrototype();

        $image = New ImagePrototype();

        $search = new Search();
        $search->AddPrototype( $comment );
        $search->AddPrototype( $user );
        $search->AddPrototype( $image );

        $search->Connect( $comment, $user, $connectiontype = 'right' );
        $search->Connect( $user, $image, $connectiontype = 'left' );

        $search->SetOrderBy( $comment, 'Created', 'DESC' );
        $search->SetGroupBy( $user, 'Id' );

        $search->Limit = 20;

        $comments = Comment_MakeTree( $search->Get( $comment ) );

        if ( empty( $comments ) ) {
            die( "No comments for user " . $userid->Get() . "!" );
        }

        DisplayChildren( $comments, 0 );
    }

?>
