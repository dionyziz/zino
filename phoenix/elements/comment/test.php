<?php

	function ElementCommentTest( tInteger $journalid ) {
		global $libs;
		global $water;
		
		$libs->Load( 'comment' );
		
		$journal = New Journal( 23 );
		
		$finder = New CommentFinder();
		$comments = $finder->FindByPage( $journal , 0 , false );
		w_assert( is_array( $comments ), '$comments must be an array, ' . gettype( $commentlist ) . ' given' );
		foreach ( $comments as $id => $commentlist ) {
			w_assert( is_numeric( $id ), 'Each $comments key must be numeric, "' . $id . '" of type ' . gettype( $id ) . ' given' );
			w_assert( is_array( $commentlist ), 'Each item of $comments must be an array, ' . gettype( $commentlist ) . ' given' );
			echo $id;
			?> => <?php
			$commentids = array();
			foreach ( $commentlist as $comment ) {
				w_assert( is_object( $comment ), '$comment must be an object, ' . gettype( $comment ) . ' given' );
				w_assert( $comment instanceof Comment, '$comment must be of class Comment, ' . get_class( $comment ) . ' given' );
				$commentids[] = $comment->Id;
			}
			echo implode( ', ', $commentids );
		}
		$water->Trace( count( $comments ) );
		?><div style="width:700px;padding-top:70px;">
		<h2><?php
		echo htmlspecialchars( $journal->Title );
		?></h2><?php
		Element( 'comment/list' , $comments , 0 , 0 );
		?></div><div class="eof"></div><?php
	}
?>
