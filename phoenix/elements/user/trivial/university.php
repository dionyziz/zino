<?php

	function ElementUserTrivialUniversity( $uni ) {
		if ( $uni->Exists() ) {
			echo htmlspecialchars( $uni->Name );
		}
	}
?>
