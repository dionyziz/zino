<?php
	function ElementCommentList( $comments ) {
		$stack = array();
		array_push( $stack, array( 0, 0 ) );
		while ( !empty( $stack ) ) {
			$item = array_pop( $stack );
			$root =  $item[0];
			$indent = $item[1];
			foreach ( $comments[ $root ] as $comment ) {
				Element( 'comment/view', $comment, $indent, isset( $comments[ $comment->Id ] ) ? count( $comments[ $comment->Id ] ) : 0 );
				if ( !isset( $comments[ $comment->Id ] ) ) {
					continue;
				}
				array_push( $stack, array( $comment->Id, $indent+1 ) );
			}
		}
	}
/*
	function ElementCommentList( $comments , $root , $indent ) {
        global $water;

		if ( !isset( $comments[ $root ] ) ) {
			return;
		}
		foreach( $comments[ $root ] as $comment ) {
			Element( 'comment/view' , $comment , $indent , isset( $comments[ $comment->Id ] ) ? count( $comments[ $comment->Id ] ) : 0 );
			Element( 'comment/list' , $comments , $comment->Id , $indent + 1 ); //Recursion
		}
	}
	*/
?>
