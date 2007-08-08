<?php
	function ElementFrontpageLeftBar() {
		?><div class="sidebar leftbar"><?php
		Element( "shout/list" );
		Element( "comment/latest" );
		Element( "user/online" );
		?></div><br /><?php
	}
?>
