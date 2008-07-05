<?php
	function ElementUserProfileSidebarWho( $theuser ) {
		?><h2><?php
			Element( 'user/avatar' , $theuser , 150 , '' , 'margin-bottom:5px' , false , 0 , 0 );
			?><span class="name"><?php
			Element( 'user/name' , $theuser , false );
			?></span>
		</h2><?php
	}
?>
