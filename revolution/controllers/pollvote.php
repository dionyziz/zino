<?php
	function Create( $pollid, $optionid ) {
		isset( $_SESSION[ 'user' ] ) or die;
		include 'model/poll.php';
		$vote->Create( $pollid, $optionid, $_SESSION[ 'user' ][ 'id' ] );	
		
		
		
	
	}
?>