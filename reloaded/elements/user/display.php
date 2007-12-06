<?php
	function ElementUserDisplay( $theuser ) {
		?><a href="user/<?php 
		echo $theuser->Username();
		?>" class="journalist"><?php
		Element( "user/icon" , $theuser , false );
		Element( "user/static" , $theuser , false , true );
		?></a><?php
	}
?>
