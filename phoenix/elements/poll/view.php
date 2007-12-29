<?php
	function ElementPollView() {
		global $page;
		
		$page->AttachStyleSheet( 'css/poll/view.css' );
		
		Element( 'user/sections' , 'poll' );
		?><div id="pollview"><?php
			Element( 'poll/small' , false ); //don't show comments number
			Element( 'comment/list' );
		?></div>
		<div class="eof"></div><?php
	}
?>