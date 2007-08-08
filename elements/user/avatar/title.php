<?php
	function ElementUserAvatarTitle( $theuser ) {
		$position = $theuser->Position();
		if ( $position == 0 ) {
			?>Ουδέτερος Χρήστης<?php
			return;
		}
		$position = floor( $position / 50 );
		if ( $position < 0 ) {
			$position = 0;
		}
		switch ( $position ) { //Shows a proper title depending on the position variable
			case 0:
				?>Αυγό<?php
				break;
			case 1:
				?>Ραγισμένο Αυγό<?php
				break;
			case 2:
				?>Μωρό Δράκος<?php
				break;
			case 3:
				?>Δράκος<?php
				break;
			case 4:
				?>Δράκος των Πάγων<?php
				break;
			case 5:
				?>Δράκος του Ήλιου<?php
				break;
			case 6:
				?>Δράκος των Βουνών<?php
				break;
			case 7:
				?>Δράκος της Φωτιάς<?php
				break;
			case 8:
				?>Δράκος του Σκότους<?php
				break;
			default:
				?>Δράκος του Φωτός<?php
				break;
		}
	}
?>
