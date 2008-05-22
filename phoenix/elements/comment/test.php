<?php

	function ElementCommentTest( tInteger $journalid ) {
		global $libs;
		global $water;
		
		$libs->Load( 'comment' );
		
		$journal = New Journal( 23 );
		
		$finder = New CommentFinder();
		$comments = $finder->FindByPage( $journal , 0 , false );
		foreach ( $comments as $id => $commentlist ) {
			echo $id;
			?> => <?php
			$commentids = array();
			foreach ( $commentlist as $comment ) {
				w_assert( $comment instanceof Comment );
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
