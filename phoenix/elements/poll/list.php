<?php
	function ElementPollList() {
		global $page;
		
		$page->AttachStyleSheet( 'css/poll/list.css' );
		
		?><div id="journallist">
			<ul>
				<li><?php
					Element( 'poll/list' );
				?></li>
				<li><?php
					Element( 'poll/list' );
				?></li>
				<li><?php
					Element( 'poll/list' );
				?></li>
				<li><?php
					Element( 'poll/list' );
				?></li>
			</ul>
		</div><?php
	}
?>