<?php
	function ElementCommentList( $comments , $root , $indent ) {
		if ( !isset( $comments[ $root ] ) ) {
			return;
		}
		foreach ( $comments[ $root ] as $comment ) {
			Element( 'comment/view' , $comment , $indent , isset( $comments[ $comment->Id() ] ) ? count( $comments[ $comment->Id() ] ) : 0 );
			Element( 'comment/list' , $comments , $comment->Id() , $indent + 1 ); // RECURSE!
		}
	}
?>