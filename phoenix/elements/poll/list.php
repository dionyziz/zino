<?php
	function ElementPollList() {
		global $page;
		
		$page->AttachStyleSheet( 'css/poll/list.css' );
		Element( 'user/sections' , 'poll' );
		?><div id="journallist">
			<ul>
				<li><?php
					Element( 'poll/small' , true );
				?></li>
				<li><?php
					Element( 'poll/small' , true );
				?></li>
				<li><?php
					Element( 'poll/small' , true );
				?></li>
				<li><?php
					Element( 'poll/small' , true );
				?></li>
			</ul>
		</div><?php
	}
?>