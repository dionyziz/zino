<?php
    class ElementUserTrivialYesno extends Element {
        protected $mPersistent = array( 'answer' );

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
