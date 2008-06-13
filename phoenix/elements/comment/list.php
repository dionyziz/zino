<?php
	function ElementCommentList( $comments ) {
        global $water;

        $water->Trace( 'In comments list: Got ' . count( $comments ) . ' comment parents and ' . count( $comments[ 0 ] ) . ' root comments' );

		$stack = array();
		for ( $i = count( $comments[ 0 ] ) - 1; $i >= 0; --$i ) {
			array_push( $stack, array( $comments[ 0 ][ $i ], 0 ) );
		}
		while ( !empty( $stack ) ) {
			$item = array_pop( $stack );
			$comment = $item[ 0 ];
			$root = $comment->Id;
			$indent = $item[ 1 ];

			Element( 'comment/view', $comment, $indent, isset( $comments[ $comment->Id ] ) ? count( $comments[ $comment->Id ] ) : 0 );
			
            if ( isset( $comments[ $root ] ) ) {
                for ( $i = count( $comments[ $root ] ) - 1; $i >= 0; --$i ) {
                    array_push( $stack, array( $comments[ $root ][ $i ], $indent + 1 ) );
                }
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
