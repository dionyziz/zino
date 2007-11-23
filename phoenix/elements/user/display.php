<?php
	function ElementUserDisplay( $theuser ) {
		if ( $theuser->Rights() <= 20 ) {
			// user
			$cssclass = "user_user";
		}
		else if ( $theuser->Rights() <= 30 ) {
			// journalist
			$cssclass = "journalist";
		}
		else if ( $theuser->Rights() <= 50 ){
			// programmer
			$cssclass = "operator";
		}
		else {
			// superuser
			$cssclass = "developer";
		}
		?><a href="user/<?php 
		echo $theuser->Username();
		?>" class="<?php 
		echo $cssclass; 
		?>"><?php
		Element( "user/icon" , $theuser , false );
		Element( "user/static" , $theuser , false , true );
		?></a><?php
	}
?>
