<?php

    class ElementUserTrivialEducation extends Element {
        protected $mPersistent = array( 'education' );

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
