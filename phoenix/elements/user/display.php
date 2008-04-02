<?php
	function ElementUserDisplay( User $theuser ) {
		?><a href="<?php
		Element( 'user/url' , $theuser );
		?>"><img src="http://static.zino.gr/phoenix/mockups/titi.jpg" class="avatar" alt="Titi" /><?php
		Element( 'user/name' , $theuser , false );
		?></a><?php
		
	
	}
?>
