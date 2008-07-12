<?php
	
	class ElementUserTrivialSex extends Element {
        public function Render( $sex , $gender ) {
            if ( $gender == 'm' || $gender == '-' ) {
                $sexes = array( 
                            '-' => '-',
                            'straight' => 'Straight',
                            'bi' => 'Bisexual',
                            'gay' => 'Gay'
                );
            }
            else {
                $sexes = array( 
                        '-' => '-',
                        'straight' => 'Straight',
                        'bi' => 'Bisexual',
                        'gay' => 'Λεσβία'
                );
            }
            echo htmlspecialchars( $sexes[ $sex ] );
        }
    }
?>
