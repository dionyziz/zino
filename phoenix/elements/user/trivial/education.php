<?php

    class ElementUserTrivialEducation extends Element {
        public function Render( $education ) {
            $educations = array( '-' => '-',
                                 'elementary' => 'Δημοτικό',
                                 'gymnasium' => 'Γυμνάσιο',
                                 'TEE'           => 'ΤΕΕ',
                                 'lyceum'       => 'Λύκειο',
                                 'TEI'          => 'ΤΕΙ',
                                 'university' => 'Πανεπιστήμιο'
            );
            echo htmlspecialchars( $educations[ $education ] );
        }
    }
?>
