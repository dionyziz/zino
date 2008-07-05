<?php
	
	function ElementUserTrivialPlace( $place ) {
		if ( $place->Exists() ) {
			echo htmlspecialchars( $place->Name );
		}
	}
?>
