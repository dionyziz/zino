<?php
	
	function ElementUserTrivialHeight( $height ) {
		if ( $height == -1 ) {
			?>-<?php
		}
		else if ( $height == -2 ) {
			?>Κάτω από 1.20m<?php
		}
		else if ( $height == -3 ) {
			?>Πάνω από 2.20m<?php
		}
		else {
			echo htmlspecialchars( $height / 100 );
			if ( ( $height % 10 == 0 ) && ( $height % 100 != 0 ) ) {
				?>0<?php
			}
			if ( $height % 100 == 0 ) {
				?>.00<?php
			}
			?>m<?php
		}
	}
?>
