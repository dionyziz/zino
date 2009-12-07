<?php

    class ElementDeveloperUserTrivialEducation extends Element {

        public function Render( $education ) {
            $educations = array(
                '-',
                'Δημοτικό',
                'Γυμνάσιο',
                'ΤΕΕ',
                'Λύκειο',
                'ΤΕΙ',
                'Πανεπιστήμιο'
            );
            echo htmlspecialchars( $educations[ $education ] );
        }
    }

?>
