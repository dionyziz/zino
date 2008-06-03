<?php

    function ActionCommentFill( tInteger $typeid, tInteger $itemid ) {
        global $libs;
        $libs->Load( 'comment' );
        $libs->Load( 'poll' );
        $libs->Load( 'journal' );
        $libs->Load( 'image/image' );

        $typeid = $typeid->Get();
        $itemid = $itemid->Get();

        $texts = array( "hey", "yo", "hahaha", "LOL", ":P", "sup", "amm", "rofl", "PWNED", "lorem ipsum", "foo bar blah", "amm", ":sheep:",
                        ":cake:", ";)", "red", "green", "blue", "white", "orange", "cyan", "yellow", "nice boobs", "faggot", "love your mom",
                        "yo mama is fat", "pervert", "bot" );
        $userids = array( 1, 2, 37, 42 );

        if ( $typeid == TYPE_JOURNAL ) {
            $texts[] = "You call this a journal?";
        }
        else if ( $typeid == TYPE_POLL ) {
            $texts[] = "I voted your mommy";
        }
        else if ( $typeid == TYPE_IMAGE ) {
            $texts[] = "You are ugly";
        }

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
                $comment->Parentid = $ids[ rand( 0, count( $ids ) - 1 ) ];
                --$next_headparent;
            }
            $comment->Save();

            $ids[] = $comment->Id;
        }

        return Redirect();
    }

?>
