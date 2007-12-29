<?php
	function ElementPollView() {
		global $page;
		
		$page->AttachStyleSheet( 'css/poll/view.css' );
		
		Element( 'user/sections' , 'poll' );
		?><div id="pollview"><?php
			Element( 'poll/small' );
			Element( 'comment/list' );
		?></div><?php
	}
?>