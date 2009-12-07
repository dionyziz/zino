<?php
    class ElementDeveloperUserTrivialYesno extends Element {

        public function Render( $answer ) {
            $yesno = array( '-'      => '-',
                            'yes' => 'Ναι', 
                            'no' => 'Όχι',
                            'socially' => 'Με παρέα'
            );
            echo htmlspecialchars( $yesno[ $answer ] );
        }
    }
?>
