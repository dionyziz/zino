<?php
	function ElementUserProfileSidebarWho( $theuser ) {
		?><h2>
			<a><img src="http://static.zino.gr/phoenix/mockups/dionyziz.200.jpg" style="width:150px;height:150px;" alt="dionyziz" title="dionyziz" /><span class="name"><?php
			Element( 'user/name' , $theuser , false );
			?></span></a>
		</h2><?php
	}
?>
