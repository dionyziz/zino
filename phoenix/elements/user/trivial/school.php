<?php

    class ElementUserTrivialSchool extends Element {
        public function Render( $school ) {
            if ( $school->Exists() ) {
                echo $school->Id;
                echo htmlspecialchars( $school->Name );
            }
        }
    }

?>
