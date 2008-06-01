<?php
	
	function ElementFrontpageCommentList() {
		global $libs;
		
		$libs->Load( 'comment' );
		
		$finder = New CommentFinder();
		$comments = $finder->FindLatest( 0 , 6 );
		?><div class="latestcomments">
			<h2>Πρόσφατα σχόλια</h2>
			<div class="list"><?php
				foreach ( $comments as $comment ) {
					Element( 'frontpage/comment/view' );
				}
			?></div><?php
		?></div><?php
	
	}
?>
