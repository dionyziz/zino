<?php

	function ElementCommentTest( tInteger $journalid ) {
		global $libs;
		global $water;
		
		$libs->Load( 'comment' );
		
		$journal = New Journal( 23 );
		
		$finder = New CommentFinder();
		$comments = $finder->FindByPage( $journal , 1 );
		$water->Trace( count( $comments ) );
		?><div style="width:700px;padding-top:70px;">
		<h2><?php
		echo htmlspecialchars( $journal->Name );
		?></h2><?php
		Element( 'comment/list' , $comments , 0 , 0 );
		?></div><div class="eof"></div><?php
	}
?>
