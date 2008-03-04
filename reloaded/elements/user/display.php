<?php
	function ElementUserDisplay( $theuser ) {
		?><a href="<?php 
		Element( 'user/url', $theuser );
		?>" class="journalist"><?php
		Element( "user/icon" , $theuser , false );
		Element( "user/static" , $theuser , false , true );
		?></a><?php
	}
?>
