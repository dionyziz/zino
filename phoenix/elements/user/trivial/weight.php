<?php
	
	class ElementUserTrivialWeight extends Element {
		protected $mPersistent = array( 'weight' );

		public function Render( $weight ) {
			if ( $weight == -3 ) {
				?><?php
			}
			else if ( $weight == -2 ) {
				?>Kάτω από 30kg<?php
			}
			else if ( $weight == -1 ) {
				?>Πάνω από 150kg<?php
			}
			else {
				echo htmlspecialchars( $weight );
				?>kg<?php
			}
		}
	}
?>
