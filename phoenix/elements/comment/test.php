<?php

	function ElementCommentTest( tInteger $journalid ) {
		$journal = New Journal( 23 );

		$finder = New CommentFinder();
		$comments = $finder->FindByPage( $journal, 0 );
		?><div style="width:700px;padding-top:70px;"><?php
		Element( 'comment/list' , $comments , 0 );
		?></div><div class="eof"></div><?php
	}
?>
