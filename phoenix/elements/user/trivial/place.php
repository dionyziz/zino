<?php
    
    class ElementUserTrivialPlace extends Element {
        public function Render( $place ) {
            if ( $place->Exists() ) {
                echo htmlspecialchars( $place->Name );
            }
        }
    }
?>
