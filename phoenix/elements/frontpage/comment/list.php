<?php
	function ElementFrontpageCommentList() {
		global $libs;

		$libs->Load( 'comment' );
		
		$finder = New CommentFinder();
		$comments = $finder->FindLatest( 0 , 10 );
		?><div class="latestcomments">
			<h2>Πρόσφατα σχόλια</h2>
			<div class="list"><?php
				foreach ( $comments as $comment ) {
					Element( 'frontpage/comment/view' , $comment );
				}
			?></div>
        <div class="eof"></div>
		<div class="more"><a href="?p=comments/recent" class="button">Όλα τα σχόλια&raquo;</a></div>
		</div><?php
	
	}
?>
