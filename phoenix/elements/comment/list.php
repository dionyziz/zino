<?php
	
    /*
    function ElementCommentList( $comments ) {
        global $water;
        global $page;
        global $user;

        $water->Trace( 'In comments list: Got ' . count( $comments ) . ' comment parents and ' . count( $comments[ 0 ] ) . ' root comments' );
		$jsarr = "Comments.numchildren = { ";
		$stack = array();
		for ( $i = count( $comments[ 0 ] ) - 1; $i >= 0; --$i ) {
			array_push( $stack, array( $comments[ 0 ][ $i ], 0 ) );
		}
		while ( !empty( $stack ) ) {
			$item = array_pop( $stack );
			$comment = $item[ 0 ];
			$root = $comment->Id;
			$indent = $item[ 1 ];
			$children = isset( $comments[ $root ] ) ? count( $comments[ $root ] ) : 0;

			Element( 'comment/view', $comment, $indent, $children );
			
			$jsarr .= $root . " : " . $children . ", ";
			
         	for ( $i = $children - 1; $i >= 0; --$i ) {
                array_push( $stack, array( $comments[ $root ][ $i ], $indent + 1 ) );
            }
		}
		if ( strlen( $jsarr ) != 25 ) { // page without comments
			$jsarr = substr( $jsarr, 0, -2);
		}
		$jsarr .= " };";
		if ( $user->Id > 0 ) {
			$page->AttachInlineScript( $jsarr );
		}
	}
    */

    function ElementCommentList( $comments ) {
        global $water;
        global $page;
        global $user;

        $indent = array(); /* comment_parentid => comment_indent */
        $indent[ 0 ] = 0;

        $children_nums = array();

        foreach ( $comments as $comment ) { 
            if ( !isset( $children_nums[ $comment->Parentid ] ) ) {
                $children_nums[ $comment->Parentid ] = 0;
            }
            $children_nums[ $comment->Parentid ] = $children_nums[ $comment->Parentid ] + 1;
        }
		
        $jsarr = "Comments.numchildren = { ";

        foreach ( $comments as $comment ) {
            $indent[ $comment->Id ] = $indent[ $comment->Parentid ] + 1;

			$jsarr .= $root . " : " . $children . ", ";

            Element( 'comment/view', $comment, $indent[ $comment->Parentid ], $children_nums[ $comment->Id ] );
        }
		
        if ( strlen( $jsarr ) != 25 ) { // page without comments
			$jsarr = substr( $jsarr, 0, -2);
		}
		$jsarr .= " };";
		if ( $user->Id > 0 ) {
			$page->AttachInlineScript( $jsarr );
	    }
    }   

?>
