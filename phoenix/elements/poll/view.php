<?php
	function ElementPollView() {
		global $page;
		
		$page->AttachStyleSheet( 'css/poll/view.css' );
		
		
		?><div id="pollview"><?php
			Element( 'poll/small' );
			Element( 'comment/list' );
		?></div><?php
	}
?>