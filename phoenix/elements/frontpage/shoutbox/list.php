<?php
	
	function ElementFrontpageShoutboxList() {
		?><div class="shoutbox">
			<h2>Συζήτηση</h2>
			<div class="comments"><?php
				Element( 'frontpage/shoutbox/reply' );
				Element( 'frontpage/shoutbox/view' );
				Element( 'frontpage/shoutbox/view' );
				Element( 'frontpage/shoutbox/view' );
				Element( 'frontpage/shoutbox/view' );
				Element( 'frontpage/shoutbox/view' );
			?></div>
		</div><?php
	}
?>