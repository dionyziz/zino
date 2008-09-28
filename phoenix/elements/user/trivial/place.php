<?php
	
	class ElementUserTrivialPlace extends Element {
		protected $mPersistent = array( 'placeid' );

		public function Render( $place, $placeid ) {
			if ( $place->Exists() ) {
				echo htmlspecialchars( $place->Name );
			}
		}
	}
?>
