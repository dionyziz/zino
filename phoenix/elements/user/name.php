<?php
	function ElementUserName( $theuser , $link = true ) {
		if ( !$link ) {
			echo htmlspecialchars( $theuser->Name );
		}
		else {
			?><a href="<?php
			Element( 'user/url' , $theuser );
			?>"><?php
			echo htmlspecialchars( $theuser->Name );
			?></a><?php
		}
	}
?>