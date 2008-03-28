<?php
	function ElementJournalList() {
		global $page;
		
		$page->AttachStyleSheet( 'css/journal/list.css' );
		$page->AttachScript( 'js/journal/list.js' );
		Element( 'user/sections' , 'journal' );
		?><div id="journallist">
			<ul>
				<li><?php
					Element( 'journal/small' );
					?><div class="barfade">
						<div class="leftbar"></div>
						<div class="rightbar"></div>
					</div>
				</li>
				<li><?php
					Element( 'journal/small' );
					?><div class="barfade">
						<div class="leftbar"></div>
						<div class="rightbar"></div>
					</div>
				</li>
				<li><?php
					Element( 'journal/small' );
					?><div class="barfade">
						<div class="leftbar"></div>
						<div class="rightbar"></div>
					</div>
				</li>
				<li><?php
					Element( 'journal/small' );
					?><div class="barfade">
						<div class="leftbar"></div>
						<div class="rightbar"></div>
					</div>
				</li>
				<li><?php
					Element( 'journal/small' );
					?><div class="barfade">
						<div class="leftbar"></div>
						<div class="rightbar"></div>
					</div>
				</li>
			</ul>
		</div><img src="http://static.zino.gr/phoenix/heart.png" style="display:none;" /><?php
	}
?>