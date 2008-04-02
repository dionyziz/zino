<?php
	function ElementUserName( $theuser , $link = true ) {
		if ( !$link ) {
			echo $theuser->Name;
		}
		else {
			?><a href="<?php
			Element( 'user/url' , $theuser );
			?>"><?php
			echo $theuser->Name;
			?></a><?php
		}
	}
?>