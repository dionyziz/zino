<?php
	function ElementCommentList( $comments ) {
		$stack = array();
		foreach( $comments[0] as $parent ) {
			array_push( $stack, array( $parent, 0 ) );
		}
		while ( !empty( $stack ) ) {
			$item = array_pop( $stack );
			$comment = $item[ 0 ];
			$root = $comment->Id;
			$indent = $item[ 1 ];

			Element( 'comment/view', $comment, $indent, isset( $comments[ $comment->Id ] ) ? count( $comments[ $comment->Id ] ) : 0 );
			
			foreach ( $comments[ $root ] as $child ) {
				array_push( $stack, array($child, $indent+1 ) );
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
