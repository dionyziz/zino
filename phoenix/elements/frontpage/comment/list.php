<?php
	
	function ElementFrontpageCommentList() {
		global $libs;
		global $water;
		$libs->Load( 'comment' );
		
		$finder = New CommentFinder();
		$comments = $finder->FindLatest( 0 , 6 );
		?><div class="latestcomments">
			<h2>Πρόσφατα σχόλια</h2>
			<div class="list"><?php
				foreach ( $comments as $comment ) {
					$water->Trace( 'comment id is ' . $comment->Id );
					Element( 'frontpage/comment/view' );
				}
			?></div><?php
		?></div><?php
	
	}
?>
