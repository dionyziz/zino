<?php

    function ActionCommentFill( tInteger $typeid, tInteger $itemid ) {
        $typeid = $typeid->Get();
        $itemid = $itemid->Get();

        $texts = array( "hey", "yo", "hahaha", "LOL", ":P", "sup", "amm", "rofl", "PWNED", "lorem ipsum", "foo bar blah" );
        $userids = array( 1, 2, 37, 42 );

        $next_headparent = 0;
        $ids = array();

        for ( $i = 0; $i < 100; ++$i ) {
            $comment = New Comment();
            $comment->Text = $texts[ rand( 0, count( $texts ) - 1 ) ];
            $comment->Userid = $userids[ rand( 0, count( $userids ) - 1 ) ];
            $comment->Typeid = $typeid;
            $comment->Itemid = $itemid;
            if ( $next_headparent == 0 ) {
                $comment->Parentid = 0;
                $next_headparent = rand( 0, 20 );
            }
            else {
                $comment->Parentid = rand( 0, count( $ids ) - 1 );
                --$next_headparent;
            }
            $comment->Save();
        }

        return Redirect();
    }

?>
