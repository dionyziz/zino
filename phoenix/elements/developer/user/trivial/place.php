<?php
    
    class ElementDeveloperUserTrivialPlace extends Element {

        public function Render( $place, $placeid ) {
            if ( $place->Exists() ) {
                echo htmlspecialchars( $place->Name );
            }
        }
    }
?>
