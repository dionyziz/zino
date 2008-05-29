<?php
	
	function ElementCommentList( $comments , $root , $indent ) {
        global $water;
        $water->Trace( 'comments listed', $comments );
        $water->Trace( 'comments count', count( $comments ) );

		if ( !isset( $comments[ $root ] ) ) {
			return;
		}
		foreach( $comments[ $root ] as $comment ) {
			Element( 'comment/view' , $comment , $indent , isset( $comments[ $comment->Id ] ) ? count( $comments[ $comment->Id ] ) : 0 );
			Element( 'comment/list' , $comments , $comment->Id , $indent + 1 ); //Recursion
		}
	}
?>
