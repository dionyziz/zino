<?php
	
	function ElementUserWeight( $weight ) {
		if ( $weight == -1 ) {
			?>-<?php
		}
		else if ( $weight == -2 ) {
			?>κάτω από 30kg<?php
		}
		else if ( $weight == -3 ) {
			?>πάνω από 150kg<?php
		}
		else {
			echo $weight;
			?>kg<?php
		}
	}
?>
