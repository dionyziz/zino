<?php

    class ElementUserTrivialEducation extends Element {
        protected $mPersistent = array( 'education' );

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
