<?php
	function ElementJournalList() {
		global $page;
		
		$page->AttachStyleSheet( 'css/journal/list.css' );
		
		Element( 'user/sections' );
		?><div id="journallist">
			<ul>
				<li><?php
				Element( 'journal/small' );
				?></li>
				<li><?php
				Element( 'journal/small' );
				?></li>
				<li><?php
				Element( 'journal/small' );
				?></li>
				<li><?php
				Element( 'journal/small' );
				?></li>
				<li><?php
				Element( 'journal/small' );
				?></li>
			</ul>
		</div><?php
	}
?>