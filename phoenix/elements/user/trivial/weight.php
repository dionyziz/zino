<?php
	
	class ElementUserTrivialWeight extends Element {
        public function Render( $weight ) {
            if ( $weight == -1 ) {
                ?>-<?php
            }
            else if ( $weight == -2 ) {
                ?>Kάτω από 30kg<?php
            }
            else if ( $weight == -3 ) {
                ?>Πάνω από 150kg<?php
            }
            else {
                echo htmlspecialchars( $weight );
                ?>kg<?php
            }
        }
    }
?>
