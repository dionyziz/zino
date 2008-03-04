<?php
	function ElementUserDisplay( $theuser ) {
		?><a href="<?php 
		Element( 'user/url', $theuser->Username() );
		?>" class="journalist"><?php
		Element( "user/icon" , $theuser , false );
		Element( "user/static" , $theuser , false , true );
		?></a><?php
	}
?>
