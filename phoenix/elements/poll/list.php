<?php
	function ElementPollList() {
		global $page;
		
		$page->AttachStyleSheet( 'css/poll/list.css' );
		
		?><div id="journallist">
			<ul>
				<li><?php
					Element( 'poll/small' );
				?></li>
				<li><?php
					Element( 'poll/small' );
				?></li>
				<li><?php
					Element( 'poll/small' );
				?></li>
				<li><?php
					Element( 'poll/small' );
				?></li>
			</ul>
		</div><?php
	}
?>